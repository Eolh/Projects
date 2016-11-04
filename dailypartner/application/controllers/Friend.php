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

        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");

        $this->load->model('FriendANDGroup');
        $this->load->model("Group");
        $this->load->model("Board_m");
        $this->load->model("UserModel");
        /* end of library */
    }

    public function index()
    {
        /* start: modelHandler */
        $friendData = new requestValue();
        $friendData->UID = $_SESSION['login']['UID'];
        /* end:   modelHandler */

        /*$data['groupInfo'] = $this->Group->getGroupList($friendData);*/
        $data['friendList'] = $this->FriendANDGroup->getUserFriend($friendData);
        $data['friendRequest'] = $this->FriendANDGroup->FriendRequest($friendData);
        $url = $_REQUEST['url'];
        $data['url'] = explode('/',$url);
        $userGroupNum = $this->FriendANDGroup->getUserGroupNum($friendData);

        if ($userGroupNum) {

            for ($i = 0; $i < count($userGroupNum); $i++) {
                $data['groupList'][] = $this->FriendANDGroup->getUserGroupList($userGroupNum[$i]);
            }

        } else
            $data['groupList'] = null;
        /* start: view */
        $this->load->view('/friend/friendGroup', $data);
        /* end:   view */
    }

    public function searchUser()
    {
        if ($_POST) {
            $searchFriend = new requestValue();
            $searchFriend->insertProperty($_POST);

            $searchData = $this->UserModel->userSearchByName($searchFriend);

            echo json_encode($searchData);
        }
    }

    public function friendadd()
    {
        if ($_POST) {
            $friendAdd = new requestValue();
            $friendAdd->insertProperty($_POST);

            $friendnum = $this->UserModel->friendadd($friendAdd);

            echo json_encode($friendnum);
        }
    }

    public function frienddelete()
    {
        if ($_POST) {
            $friendDelete = new requestValue();
            $friendDelete->insertProperty($_POST);

            $friendID = $this->UserModel->frienddelete($friendDelete);

            echo json_encode($friendID);

        }
    }

    public function friendaddlistAdd()
    {
        if ($_POST) {
            $friendaddlistAdd = new requestValue();
            $friendaddlistAdd->insertProperty($_POST);

            $friendInfo = $this->UserModel->friendaddlistAdd($friendaddlistAdd);

            $data['friendInfo'] = $friendInfo;

            $this->load->view("/friendlistAdd", $data);

        }
    }

    public function friendaddlistDelete()
    {
        if ($_POST) {
            $friendaddlistDelete = new requestValue();
            $friendaddlistDelete->insertProperty($_POST);

            $this->UserModel->friendaddlistDelete($friendaddlistDelete);

        }
    }

    public function addgroup()
    {
        if ($_POST) {
            $groupName = new requestValue();
            $groupName->insertProperty($_POST);

            $curdate = $this->Board_m->Now();
            $teamInfo = $this->UserModel->groupadd($groupName, $curdate);
            $this->Board_m->addGroupBoard($groupName, $curdate);

            /*$dirname = "../../public/file/$groupName->gname";*/
            /*die(var_dump($dirname));*/

            $dirname = "./public/file/group_".$teamInfo[0]->gnum;

            mkdir($dirname, 0777, true);

            $data['teamInfo'] = $teamInfo;

            $this->load->view("/grouplistAdd", $data);
        }
    }

    public function grouplistView()
    {
        if ($_POST) {
            $groupNum = new requestValue();
            $groupNum->insertProperty($_POST);
            /* end:   modelHandler */

            $groupMemberUID = $this->UserModel->SearchMember($groupNum);

            for ($i = 0; $i < count($groupMemberUID); $i++) {
                $data['groupMemberList'][] = $this->UserModel->groupMemberList($groupMemberUID[$i]);
            }

            $this->load->view("/groupmemberList", $data);
        }
    }

    public function friend_in_group_button()
    {
        $friendData = new requestValue();
        $friendData->UID = $_SESSION['login']['UID'];

        $friendList = $this->FriendANDGroup->getUserFriend($friendData);

        echo json_encode($friendList);
    }

    public function add_friend_in_group()
    {
        if ($_POST) {
            $addMemberInfo = new requestValue();
            $addMemberInfo->insertProperty($_POST);

            $checkMemberIngroup = $this->UserModel->checkMemberIngroup($addMemberInfo);

            if (!$checkMemberIngroup) {
                $data['MemberInfo'] = $this->UserModel->addMemberinGroup($addMemberInfo);
            } else {
                $data['MemberInfo'] = null;
            }

            $this->load->view("/addgroupmember", $data);
        }
    }
}