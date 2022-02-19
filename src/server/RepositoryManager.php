<?php

namespace App;

class RepositoryManager {

    const REPOSITORY_DIR = "repos";

    public function __construct(){
        
        // If repository dir do not exist yet
        if(!is_dir(self::REPOSITORY_DIR)){
            \mkdir(self::REPOSITORY_DIR);
        }
    }

    public function addRepository($repo_url){

        $outputs = null;
        $command_result = null;

        $command_change_dir = "cd " . self::REPOSITORY_DIR;
        $command_clone_project = "git clone " . $repo_url;

        // change to repo directory and clone new project
        exec($command_change_dir . " && " . $command_clone_project, $outputs, $command_result);

        // command has succeed
        if($command_result == 0){
            return true;
        }

        // command has failed
        else {
            return false;
        }
    }

}