<?php 
namespace AppBundle\Lib\Pipedrive\Library;

/**
 * Pipedrive Files Methods
 *
 * Files are documents of any kind (images, spreadsheets, text files, etc) that 
 * are uploaded to Pipedrive, and usually associated with a particular Deal, 
 * Person or Organization. Note that the API currently does not support uploading 
 * files although it lets you retrieve file meta info along retrieve a download 
 * URL where the file can be downloaded using a standard HTTP GET request.
 *
 */
class Files
{
    /**
     * Hold the pipedrive cURL session
     * @var \AppBundle\Lib\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;

    /**
     * Initialise the object load master class
     */
    public function __construct(\AppBundle\Lib\Pipedrive\Pipedrive $master)
    {
        //associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns a array of files
     *
     * @return array returns array of files
     */
    public function getAll()
    {
        return $this->curl->get('files');
    }
    
    /**
     * Returns a file
     *
     * @param  int   $id pipedrive file id
     * @return array returns detials of a file
     */
    public function getById($id)
    {
        return $this->curl->get('files/' . $id);
    }

    /**
     * Returns a file binary
     *
     * @param  int   $id pipedrive file id
     * @return file returns binary content of a file
     */
    public function getRealDownloadUrlById($id)
    {
        // Pipedrive redirects to AmazonS3 servers when all OK
        // So we need redirect URI
        $result = $this->curl->get('files/' . $id . '/download');
        //$redir = curl_getinfo($this->curl->curl, CURLINFO_EFFECTIVE_URL);
        $info = curl_getinfo($this->curl->curl);
        $redirect = $info['redirect_url'];
        return $redirect;
    }
    
    /**
     * Adds a file
     *
     * @param  array $data file detials
     * @return array returns detials of the file
     */
    public function add(array $data)
    {
        //if there is no title set throw error as it is a required field
        if (!isset($data['file'])) {
            throw new \Exception('You must include a "file" field when inserting a file');
        }

        return $this->curl->post('files', $data);
         //return $this->curl->post_files('files', $data, $files);
    }

}