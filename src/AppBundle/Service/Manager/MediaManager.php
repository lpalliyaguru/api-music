<?php

namespace AppBundle\Service\Manager;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\FileSystem;

class MediaManager
{
    private static $allowedMimeTypes = array('image/jpeg', 'image/png');
    private $filesystem;

    public function __construct(FileSystem $fileSystem, $buketURL)
    {
        $this->filesystem = $fileSystem;
        $this->bucketURL = $buketURL;
    }

    public function upload(UploadedFile $file, $s3DirName, $fullURL = false)
    {
        // Check if the file's mime type is in the list of allowed mime types.
        if (!in_array($file->getClientMimeType(), self::$allowedMimeTypes)) {
            throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed.', $file->getClientMimeType()));
        }
        // Generate a unique filename based on the date and add file extension of the uploaded file
        $filename = sprintf('%s/%s.%s', $s3DirName, uniqid(), $file->getClientOriginalExtension());
        $adapter = $this->filesystem->getAdapter();

        $adapter->setMetadata($filename, array('contentType' => $file->getClientMimeType()));
        $adapter->write($filename, file_get_contents($file->getPathname()));

        if($fullURL) {
            $filename = $this->bucketURL . $filename;
        }

        return $filename;
    }

    private function guessMimeType($extension)
    {
        $mimeTypes = array(
            'txt'  => 'text/plain',
            'htm'  => 'text/html',
            'html' => 'text/html',
            'php'  => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'xml'  => 'application/xml',
            'swf'  => 'application/x-shockwave-flash',
            'flv'  => 'video/x-flv',
            // images
            'png'  => 'image/png',
            'jpe'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'gif'  => 'image/gif',
            'bmp'  => 'image/bmp',
            'ico'  => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif'  => 'image/tiff',
            'svg'  => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip'  => 'application/zip',
            'rar'  => 'application/x-rar-compressed',
            'exe'  => 'application/x-msdownload',
            'msi'  => 'application/x-msdownload',
            'cab'  => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3'  => 'audio/mpeg',
            'qt'   => 'video/quicktime',
            'mov'  => 'video/quicktime',
            // adobe
            'pdf'  => 'application/pdf',
            'psd'  => 'image/vnd.adobe.photoshop',
            'ai'   => 'application/postscript',
            'eps'  => 'application/postscript',
            'ps'   => 'application/postscript',
            // ms office
            'doc'  => 'application/msword',
            'rtf'  => 'application/rtf',
            'xls'  => 'application/vnd.ms-excel',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',
            // open office
            'odt'  => 'application/vnd.oasis.opendocument.text',
            'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        if (array_key_exists($extension, $mimeTypes)){
            return $mimeTypes[$extension];
        } else {
            return 'application/octet-stream';
        }
    }
}