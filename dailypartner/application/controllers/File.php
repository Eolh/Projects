<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('TagANDPersonal_tag');
        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        $this->load->library("HashTag");
        /* end of library */
    }

    public function index($tag = null)
    {
        /* start: modelHandler */
        $fileData = new requestValue();
        $fileData->UID = $_SESSION['login']['UID'];
        $fileData->tag = $tag;
        $url = $_REQUEST['url'];
        $data['url'] = explode('/',$url);
        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();
        $data['tagList']  = $this->TagANDPersonal_tag->getTagListByUID($fileData);
        $data['cntFiles'] = $this->TagANDPersonal_tag->getCountFiles($fileData);
        $data['selectedName'] = $tag;

        /* 유저가 가진 모든 회원 리스트 출력 */
        /*if($fileData->tag != null)
            $data[ 'fileList' ] = $this->TagANDPersonal_tag->getFileListByUIDAndTag($fileData);
        else
            $data['fileList'] = $this->TagANDPersonal_tag->getFileListByUID($fileData);*/

        /* 현재 페이지의 파일에 대한 리스트 */

        for($x=0 ; $x<count($data['tagList']) ; $x++)
        {
            $fileData->hashTag = $data['tagList'][$x]->tagName;
            $data['tagList'][$x]->fileList = $this->TagANDPersonal_tag->getFileListByTag($fileData);
        }   /* 태그 별로 수를 세기 위함... */

        //getArgDumpDied($data['fileList']);

        $this->load->view('/file/file_list', $data);
    }

    public function FileList($tag = null)
    {
        /* start: modelHandler */
        $fileData = new requestValue();
        $fileData->UID = $_SESSION['login']['UID'];
        $fileData->tag = $tag;
        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();
        $data['tagList']  = $this->TagANDPersonal_tag->getTagListByUID($fileData);
        $data['cntFiles'] = $this->TagANDPersonal_tag->getCountFiles($fileData);
        $data['selectedName'] = $tag;

        /* 유저가 가진 모든 회원 리스트 출력 */
        if($fileData->tag != null)
            $data[ 'fileList' ] = $this->TagANDPersonal_tag->getFileListByUIDAndTag($fileData);
        else
            $data['fileList'] = $this->TagANDPersonal_tag->getFileListByUID($fileData);

        /* 현재 페이지의 파일에 대한 리스트 */

        for($x=0 ; $x<count($data['tagList']) ; $x++)
        {
            $fileData->hashTag = $data['tagList'][$x]->tagName;
            $data['tagList'][$x]->fileList = $this->TagANDPersonal_tag->getFileListByTag($fileData);

        }   /* 태그 별로 수를 세기 위함... */

        //getArgDumpDied($data['fileList']);

        $this->load->view('/file/file_view', $data);
    }

    public function reIndex($tag = null)
    {
        $this->index(urldecode($tag));
    }

    public function fileLister()
    {
        /* start: modelHandler */
        $fileData = new requestValue();
        $fileData->UID = $_SESSION['login']['UID'];
        $fileData->tag = isset($_POST['tag']) ? $_POST['tag'] : null;
        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();

        /* 유저가 가진 모든 회원 리스트 출력 */
        if($fileData->tag != null)
            $data[ 'fileList' ] = $this->TagANDPersonal_tag->getFileListByUIDAndTag($fileData);
        else
            $data['fileList']   = $this->TagANDPersonal_tag->getFileListByUID($fileData);

        /* 현재 페이지의 파일에 대한 리스트 */
        $this->load->view('/file/fileLister', $data);
    }

    public function fileListerSearch()
    {
        /* start: modelHandler */
        $fileData = new requestValue();
        $fileData->UID    = $_SESSION['login']['UID'];
        $fileData->tag    = isset($_POST['tag']) ? $_POST['tag'] : null;
        $fileData->search = isset($_POST['search']) ? $_POST['search'] : null;
        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();

        /*if($fileData->tag != null)
            $data[ 'fileList' ] = $this->TagANDPersonal_tag->getFileListByUIDAndTag($fileData);
        else
            $data['fileList']   = $this->TagANDPersonal_tag->getFileListByUID($fileData);*/

        $data['fileList'] = $this->TagANDPersonal_tag->getFileListByUIDAndSearch($fileData);

        $this->load->view('/file/fileLister', $data);
    }


    public function tagTest($str = "sd;qwe;frfr;asd;asdsdfsdf")
    {
        $test = new HashTag();

        $result = $test->convertFileTags($str);
        echo $result;
    }
}