<?php
namespace App\Helpers;

use \Illuminate\Support\Facades\File;

/**
 * OCRHelper Class
 * 
 * Manages the conversion of PDF,Office and Image Files.
 * 
 * This Class has 2 main functions:
 * 1) Extracting the text files of every office, pdf document
 * 2) Convert every office document to PDF
 * 
 * @author Mirko Rosenthal <mirko.rosenthal@webbite.de>
 * @version 1.0.0
 */
class OcrHelper{

    /**
     * Path to the upload folder
     * @var string 
     */
    protected $storage_path;
    
    /**
     * Filename without extension
     * 
     * @var string
     */
    protected $file_name;
    
    /**
     * File Extension without file name
     * 
     * @var string 
     */
    protected $file_extension;
    
    /**
     * File Name with Extension
     * @var string 
     */
    protected $file_basename;
    
    /**
     * The extracted Text
     * 
     * @var string 
     */
    protected $text;
    
    /**
     * Constructor Method
     * 
     * @param string $storage_path Path to the Upload Folder (public/files/documents/juristenportal/ocr/)
     * @param string $file_basename Full name of the file without Path (2017-02-22-10:22:00-001.docx)
     */
    public function __construct($storage_path, $file_basename){
        $this->setFilename($file_basename);
        $this->setStoragePath($storage_path);
    }
    
    /**
     * Sets the storage path
     * 
     * @param string $path Path to storage dir (public/files/documents/juristenportal/ocr/)
     */
    public function setStoragePath($path){
        $this->storage_path = $path;
    }

    /**
     * Sets the file name
     * 
     * @param string $file_basename
     */
    public function setFilename($file_basename){
        $path_parts = pathinfo($file_basename);

        $this->file_name = $path_parts['filename'];
        $this->file_extension = strtolower($path_parts['extension']);
        $this->file_basename = $path_parts['basename'];
    }
    
    public function getFileName(){
        return $this->file_name;
    }
    
    public function getFileExtension(){
        return $this->file_extension;
    }
    
    public function getFileBaseName(){
        return $this->file_basename;
    }
    
    /**
     * Returns the extravted text
     * 
     * @return string
     */
    public function getText(){
        return $this->text;
    }
    
    /**
     * Extracts the Text from any filetype
     * 
     * @return string the extracted text
     */
    public function extractText(){
        switch($this->file_extension){
            case 'doc':
            case 'docx':
                $this->extractWord();
                break;
            case 'xls':
            case 'xlsx':
                $this->extractExcel();
                break;
            case 'ppt':
            case 'pptx':
                $this->extractPowerPoint();
                break;
            case 'pdf':
                $this->extractPDF();
                break;
            case 'png':
            case 'tif':
            case 'jpg':
                $this->extractImage();
                break;
            default:
                break;
        }
        $this->cleanUp();
        return $this->getText();
    }
    
    /**
     * Converts any filetype to pdf
     * 
     * @return string name of the pdf file
     */
    public function convertToPDF(){
            $file = false;
        switch($this->file_extension){
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'ppt':
            case 'pptx':
                $file = $this->convertOfficeToPDF();
                break;
            case 'png':
            case 'tif':
            case 'jpg':
                $file = $this->convertImageToPDF();
                break;
            case 'pdf':
                $file = $this->convertPDFToSearchablePDF();
            default:
                break;
        }
        $this->cleanUp();
        return $file;
    }
    
    /**
     * Converts any filetype to pdf
     * 
     * @return string name of the pdf file
     */
    public function convertToPdfObject(){
        $file = new \StdClass();
        $file->filename = $this->file_basename;
        switch($this->file_extension){
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'ppt':
            case 'pptx':
                $file->object = $this->convertOfficeToPDF();
                break;
            case 'png':
            case 'tif':
            case 'jpg':
                $file->object = $this->convertImageToPDF();
                break;
            case 'pdf':
                $file->object = $this->convertPDFToSearchablePDF();
            default:
                break;
        }
        $this->cleanUp();
        return $file;
    }
    
    
    /**
     * cd to Folder for the system() call
     * 
     * @return string cd <FOLDERNAME> &&
     */
    private function cdToFolder(){
        return 'cd ' . $this->storage_path . ' && ';
    }
    
    /**
     * export HOME for the system() call
     * 
     * @return string export HOME=<STORAGEPATH> &&
     */
    private function setHome(){
        return 'export HOME='. $this->storage_path . ' && ';
    }
    
    /**
     * Removes tmp files
     */
    public function cleanUp(){
        File::delete($this->storage_path . 'output.png');
        File::delete($this->storage_path . 'output.txt');
    }
    
    /**
     * Parses the output.txt file
     * 
     * This function checks if a output file exists and parses the text
     * If no file exists it will set an empty string
     */
    private function parseText(){
        if(File::exists($this->storage_path . 'output.txt')){
            $this->text = File::get($this->storage_path . 'output.txt');
        }else{
            $this->text = '';
        }
    }
    
    /**
     * Extract data from a pdf file
     */
    private function extractPDF(){
        $this->extractTextFromPDF();
        if(strlen($this->text) < 5){
            if($this->convertPDFToPNG()){
                $this->extractImage();
            }
        }
    }
    
    /**
     * Extract the text from a pdf file
     */
    private function extractTextFromPDF(){
        $cmd = $this->cdToFolder() 
                . 'pdftotext ' . escapeshellcmd($this->file_basename) .  ' output.txt';
        exec($cmd);
        $this->parseText();
        $this->cleanUp();
    }
    
    /**
     * Converts a PDF File to PNG for OCR
     * 
     * @return boolean true on success | false on fail
     */
    private function convertPDFToPNG(){
        $cmd = $this->cdToFolder()
                . 'gs -dSAFER -sDEVICE=png16m -dINTERPOLATE -dNumRenderingThreads=4 -r600 -o output.png -c 300000000 setvmthreshold -f ' . escapeshellcmd($this->file_basename);
        exec($cmd);
        if(File::exists($this->storage_path . 'output.png')){
            $this->cleanUp();
            $this->file_basename = 'output.png';
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Extract text from an image (OCR)
     */
    private function extractImage(){
        $cmd = $this->cdToFolder()
                . 'tesseract ' . escapeshellcmd($this->file_basename) . ' output -l deu+eng';
        exec($cmd);
        $this->parseText();
    }
    
    /**
     * Extract text from a word document
     */
    private function extractWord(){
        
        $cmd = $this->setHome()
               . $this->cdToFolder()
               . 'libreoffice --invisible --headless --convert-to txt:Text ' . escapeshellcmd($this->file_basename);
        exec($cmd);
        if(File::exists($this->storage_path . $this->file_name . '.txt')){
            File::move($this->storage_path . $this->file_name . '.txt', $this->storage_path . 'output.txt');
        }
        $this->parseText();
    }
    
    /**
     * Extract text from a excel document
     */
    private function extractExcel(){
        $cmd = $this->setHome()
                . $this->cdToFolder()
                . 'libreoffice --invisible --headless --convert-to csv --infilter=CSV:44,34,76,1 ' . escapeshellcmd($this->file_basename);
        exec($cmd);
        if(File::exists($this->storage_path . $this->file_name . '.csv')){
            File::move($this->storage_path . $this->file_name . '.csv', $this->storage_path . 'output.txt');
        }
        $this->parseText();
    }
    
    /**
     * Extract text from powerpoint document
     */
    private function extractPowerPoint(){
        $cmd = $this->setHome()
                . $this->cdToFolder()
                . 'libreoffice --invisible --headless --convert-to pdf ' . escapeshellcmd($this->file_basename);
        exec($cmd);
        if(File::exists($this->storage_path . $this->file_name . '.pdf')){
            $this->file_basename = $this->file_name . '.pdf';
            $this->extractTextFromPDF();
        }
    }
    
    /**
     * Convert any office file (doc,xls,ppt,docx,xlsx,pptx) to pdf
     * 
     * @return boolean|string file name if successful | false on fail
     */
    private function convertOfficeToPDF(){
        $cmd = $this->setHome()
                . $this->cdToFolder()
                . 'libreoffice --invisible --headless --convert-to pdf ' . escapeshellcmd($this->file_basename);
        exec($cmd, $ret);
        if(File::exists($this->storage_path . $this->file_name . '.pdf')){
           return $this->file_name . '.pdf';
        }else{
            return false;
        }
    }
    
    
    public function getMetaData(){
        $output = '';
        $cmd = $this->setHome()
                .  $this->cdToFolder()
                . 'exiftool '. escapeshellarg($this->file_basename);
        exec($cmd, $output, $ret);
        $values = [];
        
        foreach($output as $line){
            $parts = explode(':',$line, 2);
            if(count($parts) != 2){
                continue;
            }
            
            //Remove all whitespaces in the key because blade templates can't handle them
            $key = str_replace(' ', '', $parts[0]);

            $values[$key] = trim($parts[1]);
        }
        
        return $values;
    }
    
    /**
     * Converts a image PDF to a searchable PDF
     * 
     * @return string Name of the PDF File
     */
    private function convertPDFToSearchablePDF(){
        /* Disabled because we don't want to overlay the image pdf
        $this->extractTextFromPDF();
        
        if(strlen($this->text) < 5){
            $this->convertPDFToPNG();
            return $this->convertImageToPDF();
        }
        */
        return $this->file_basename;
    }
    
    /**
     * Converts image to pdf with text overlay
     * 
     * @return boolean|string file name if successful | false on fail
     */
    private function convertImageToPDF(){
        $cmd = $this->setHome()
                . $this->cdToFolder()
                . 'tesseract -l deu+eng ' . $this->file_basename . ' ' . $this->file_basename . '  pdf';
        exec($cmd);
        if(File::exists($this->storage_path . 'output.png.pdf')){
            return $this->storage_path . 'output.png.pdf';
        }else{
            return false;
        }
    } 
}