<?php
    // Helper class used for a variety of support functions
    class Misc
    {
        protected $db;

        // Db connection
        public function __construct()
        {
            $this->db = new Database();
        }

        public function readVar($type, $var)
        {
            if($type == 'POST')
            {
                if(isset($_POST[$var]) && !empty($_POST[$var]) && ($_POST[$var] != '"' && $_POST[$var] != "'" && $_POST[$var] != '<' && $_POST[$var] != '>' && $_POST[$var] != '&'))
                {
                    return true;
                }
            }
            else
            {
                if(isset($_GET[$var]) && !empty($_GET[$var]) && ($_GET[$var] != '"' && $_GET[$var] != "'" && $_GET[$var] != '<' && $_GET[$var] != '>' && $_GET[$var] != '&'))
                {
                    return true;
                }
            }
        }

        public function validateUserRights($thread_ID)
        {
            $sth = $this->db->selectDatabase('thread','thread_id',$thread_ID,'');
            if($row = $sth->fetch())
            {
                $user = new User($_SESSION['Username']); 
                $user_ID = $user->id;
                // Not yet implemented: 
                $permission = $user->permission;

                if($row['user_ID'] == $user_ID)
                {
                    // Thread belongs to logged in user
                    return true;
                }
                elseif($permission == '2' || $permission == '3') 
                {
                    // Logged in user is teacher or admin
                    return true;
                }
                else
                {
                    return false;
                }

            } 
        }

        // Highlight menu button of current page
        public function menuCurrentPage($pageStr, $currentPage)
        {
            if($pageStr == $currentPage)
            {
                echo 'text-decoration: underline;';
            }
        }
    }
?>