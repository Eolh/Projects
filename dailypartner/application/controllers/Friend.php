<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: SAMSUNG
 * Date: 2016-07-21
 * Time: 오후 10:46
 */
class Friend extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('FriendANDGroup');
        $this->load->model("Group");
        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        /* end of library */
    }

    public function index()
    {
        /* start: modelHandler */
        $friendData = new requestValue();
        $friendData->UID   = $_SESSION['login']['UID'];
        /* end:   modelHandler */

        /*$data['groupInfo'] = $this->Group->getGroupList($friendData);*/
        $data['friendList'] = $this->FriendANDGroup->getUserFriend($friendData);
        $data['friendRequest'] = $this->FriendANDGroup->FriendRequest($friendData);

        $userGroupNum = $this->FriendANDGroup->getUserGroupNum($friendData);

        if($userGroupNum) {

            for ($i = 0; $i < count($userGroupNum); $i++) {
                $data['groupList'][] = $this->FriendANDGroup->getUserGroupList($userGroupNum[$i]);
            }
        }
        else
            $data['groupList'] = null;
        /* start: view */
        $this->load->view('/friend/friendGroup', $data);
        /* end:   view */
    }
}