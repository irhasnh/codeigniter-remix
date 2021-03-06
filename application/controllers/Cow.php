<?php
/**
*  Beberapa fungsi diambil dari source avenir dan selebihnya kreasi kami sendiri
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Cow extends CI_Controller {
    private $_c_extends;
    private $_mo_extends;
    private $_mi_extends;
    private $_templateLoc;
    private $_tab 		= "\t";
    private $_tab2 		= "\t\t";
    private $_tab3 		= "\t\t\t";

    private $_ret 		= "\n";
    private $_ret2 		= "\n\n";
    private $_rettab 	= "\n\t";
    private $_tabret	= "\t\n";
    private $_replace 	= array();
  

    public function __construct()
    {
    	parent::__construct();
        define("controller_path",APPPATH.'controllers/');
        define("model_path", APPPATH.'models/');
        define("view_path", APPPATH.'views/');
        define("assets_path", FCPATH.'assets/');

        
        $this->config->load('cow',TRUE);
        $this->_templateLoc = APPPATH.$this->config->item('templates', 'cow');
        $this->_c_extends = $this->config->item('c_extends', 'cow');
        $this->_mo_extends = $this->config->item('model_extends', 'cow');
        echo "
             _ (.\".) _
            '-'/. .\'-'
              /_   _\     _...._
             (` o o `)---`      '.
       m0oow  /\"---\"`             \
              |            /     ;|
              |           |      ||
               \\   \  \  \     /\\
                \`;-'| |-.-'-,  \ |)
                 ( | ( | `-uu ( |
                  ||  ||    || ||
                 /_( /_(   /_(/_(
";
        echo "\033[33m  ____  _   _ ____     ____ _____        __
 |  _ \| | | |  _ \   / ___/ _ \ \      / /
 | |_) | |_| | |_) | | |  | | | \ \ /\ / / 
 |  __/|  _  |  __/  | |__| |_| |\ V  V /  
 |_|   |_| |_|_|      \____\___/  \_/\_/   
                                           {$this->_ret2} \033[37m";
        echo "\033[0;32mPHP Version : ".phpversion()."\n";
        echo "\033[0;32m CI Version  : ".CI_VERSION."\n\033[37m";
        echo "\033[0;32m Architectur : ".$this->isHMVC()."\n\033[37m";


    }

    public function _remap($method, $params=array())
    {
        if(strpos($method,':'))
        {
            $method = str_replace(':','_',$method);
        }
        if(method_exists($this,$method))
        {
            return call_user_func_array(array($this,$method),$params);
        }
    }

    public function index()
    {
    	echo "{$this->_tab3}Welcome to PHP Cow\nThank you to : Allah SWT , Prophet Muhammad SAW, Avenir, And All My Friends\n type help to show available commands\n";
    }


    public function help()
    {
    	echo "==[ Available Commands ]== {$this->_ret}";
    	echo "- cow create:app{$this->_ret}";
    	echo "- cow create:controller{$this->_ret}";
    	echo "- cow create:model{$this->_ret}";
    	echo "- cow create:view{$this->_ret}";
    	echo "- cow create:crud{$this->_ret}";
        echo "- cow create:hmvc{$this->_ret}";
    }

	public function create($what = NULL, $name = NULL)
    {
        $what = filter_var($what, FILTER_SANITIZE_STRING);
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $can_create = array('app','controller','crud','auth','dashboard','model','view','migration');

        if(in_array($what, $can_create))
        {
            if(empty($name) && $what != "crud" && $what != "auth" && $what != "dashboard")
            {
                echo  $this->_ret.'You didn\'t provide a name for '.$what;
                return FALSE;
            }
            switch($what)
            {
                case 'app':
                    $this->create_app($name);
                    break;
                case 'crud':
                    $this->create_crud($name);
                    break;
                case 'auth':
                    $this->create_auth($name);
                    break;
                case 'dashboard':
                    $this->create_dashboard($name);
                    break;
                case 'controller':
                    $this->create_controller($name);
                    break;
                case 'model':
                    $this->create_model($name);
                    break;
                case 'view':
                    $this->create_view($name);
                    break;
            }
        }
        else
        {
            echo  $this->_ret.'Command not available!';
        }
    }


  	public function create_app($app = NULL)
    {
        if(isset($app))
        {
            if(file_exists(controller_path.ucfirst($app).'.php') || (class_exists(''.$app.'')) || (class_exists(''.$app.'_model')))
            {
                echo $app." Controller or Model already exists in the application/controllers directory.\n";
            }
            else
            {
                $this->create_controller($app);
                $this->create_model($app);
                $this->create_view($app);
            }
        }
        else
        {
            echo $this->_ret.'You need to provide a name for the app';
        }
    }


    public function create_crud()
    {
            $host = getenv('DB_HOST');
            $user = getenv('DB_USERNAME');
            $pass = getenv('DB_PASSWORD');
            $db   = getenv('DB_NAME');

            //create table
            $ci = &get_instance();
            $ci->load->dbforge();
                $fields = array(
                    'id' => array(
                            'type' => 'INT',
                            'constraint' => 11,
                            'unsigned' => TRUE,
                            'auto_increment' => TRUE
                    ),
                    'title' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '100',
                    ),
                    'image' => array(
                            'type' =>'VARCHAR',
                            'constraint' => '255',
                            'default' => '',
                    ),
                    'status' => array(
                            'type' => "enum('active','nonactive','deleted')",
                    ),
                    'created_at' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '100',
                    ),
                    'created_by' => array(
                            'type' => 'INT',
                            'constraint' => '11',
                    ),
                    'modified_at' => array(
                            'type' => 'datetime',
                            'null' => TRUE
                    ),
                    'modified_by' => array(
                            'type' => 'INT',
                            'constraint' => '11',
                            'null' => TRUE
                    ),

                );
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->add_field($fields);
                $this->dbforge->create_table('ads',TRUE);
                $oldmask = umask(0);
                mkdir(FCPATH.'assets/uploads/ads', 0777, true);
                umask($oldmask);
            if (!file_exists(controller_path."Ads.php") && model_path."Adsmodel.php") {
               //create controller
                $this->fileLoader(controller_path."Ads.php","https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CRUD-CI-Remix/master/controllers/Ads.php");

                //create model
                $this->fileLoader(model_path."Adsmodel.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CRUD-CI-Remix/master/models/Adsmodel.php");
            }


            //create views
            if (!is_dir(view_path.'ads')) {
                mkdir(view_path.'ads', 0777, true);
                $this->fileLoader(view_path."ads/index.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CRUD-CI-Remix/master/ads/index.php");
                $this->fileLoader(view_path."ads/add.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CRUD-CI-Remix/master/ads/add.php");
                $this->fileLoader( view_path."ads/edit.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CRUD-CI-Remix/master/ads/edit.php");
            }
           
    }


    public function create_auth($app = NULL)
    {

        /////////////////////////////////////////
        ///// BEGIN Proc Controller & Model /////
        ////////////////////////////////////////
            if (!file_exists(controller_path."/Auth.php") && model_path."Authmodel.php") {
               //create controller
                $this->fileLoader(controller_path."Auth.php","https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Auth/master/controllers/Auth.php");

                //create model
                $this->fileLoader(model_path."Authmodel.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Auth/master/models/Authmodel.php");
            }
        /////////////////////////////////////////
        ///// END Proc Controller & Model ///////
        ////////////////////////////////////////


        /////////////////////////////
        ///// BEGIN Proc Views /////
        ////////////////////////////
        if (!is_dir(view_path.'auth')) {
            mkdir(view_path.'auth', 0777, true);
        }

            $this->fileLoader(view_path."auth/index.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Auth/master/views/auth/index.php");

        if (!is_dir(view_path.'template-auth')) {
            mkdir(view_path.'template-auth', 0777, true);
        }

            $this->fileLoader(view_path."template-auth/header.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Auth/master/views/template-auth/header.php");
            $this->fileLoader(view_path."template-auth/footer.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Auth/master/views/template-auth/footer.php");
        /////////////////////////////
        ///// BEGIN Proc Views /////
        ////////////////////////////


        //////////////////////////////////
        ///// BEGIN Proc Auth Assets /////
        /////////////////////////////////
        if (!is_dir(assets_path.'auth')) {
            mkdir(assets_path.'auth', 0777, true);
        }


        if (is_file(assets_path."auth-assets.zip") == false) {
            $this->loadZipGitHub(assets_path."auth-assets.zip", "http://kryptonraven.com/cdn/ci-remix/auth-assets.zip");
        }

        $zip = new ZipArchive;
        $res = $zip->open(assets_path.'auth-assets.zip');
        if ($res === TRUE) {
          $zip->extractTo(assets_path.'');
          $zip->close();
          unlink(assets_path."auth-assets.zip");
        } else {
          echo 'Something Error';
        }

        //////////////////////////////////
        ///// END Proc Auth Assets /////
        /////////////////////////////////
        $this->create_dashboard();

    }

    public function create_dashboard($app = null)
    {
        /////////////////////////////////////////
        ///// BEGIN Proc Controller & Model /////
        ////////////////////////////////////////

        if (!is_dir("Admin")) {
            mkdir(controller_path.'Admin', 0777, true);
        }
            if (!file_exists(controller_path."Admin/Dashboard.php")) {
               //create controller
                $this->fileLoader(controller_path."Admin/Dashboard.php","https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Dashboard-/master/controllers/Admin/Dashboard.php");

                $this->fileLoader(controller_path."Admin/Users.php","https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Dashboard-/master/controllers/Admin/Users.php");
            }
        /////////////////////////////////////////
        ///// END Proc Controller & Model ///////
        ////////////////////////////////////////


        /////////////////////////////
        ///// BEGIN Proc Views /////
        ////////////////////////////
        if (!is_dir(view_path.'admin')) {
            mkdir(view_path.'admin', 0777, true);
        }

            $this->fileLoader(view_path."admin/dashboard.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Dashboard-/master/views/admin/dashboard.php");
            $this->fileLoader(view_path."admin/users.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Dashboard-/master/views/admin/users.php");

        if (!is_dir(view_path.'template-backend')) {
            mkdir(view_path.'template-backend', 0777, true);
        }

            $this->fileLoader(view_path."template-backend/header-dashboard.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Dashboard-/master/views/template-backend/header-dashboard.php");
            $this->fileLoader(view_path."template-backend/footer-dashboard.php", "https://raw.githubusercontent.com/robyfirnandoyusuf/Starter-CI-Remix-Dashboard-/master/views/template-backend/footer-dashboard.php");
        /////////////////////////////
        ///// BEGIN Proc Views /////
        ////////////////////////////


        //////////////////////////////////
        ///// BEGIN Proc Auth Assets /////
        /////////////////////////////////

        if (is_file(assets_path."dashboard-assets.zip") == false) {
            $this->loadZipGitHub(assets_path."dashboard-assets.zip", "http://kryptonraven.com/cdn/ci-remix/dashboard-assets.zip");
        }

        $zip = new ZipArchive;
        $res = $zip->open(assets_path.'dashboard-assets.zip');
        if ($res === TRUE) {
          $zip->extractTo(assets_path.'backend/');
          $zip->close();
          unlink(assets_path."dashboard-assets.zip");
        } else {
          echo '<font color="red">Something Error</font>';
        }

        //////////////////////////////////
        ///// END Proc Auth Assets /////
        /////////////////////////////////
    }

    public function create_controller()
    {
        $available = array('extend'=>'extend','e'=>'extend');
        $params = func_get_args();
        $arguments = array();
        
        foreach($params as $parameter)
        {
            $argument = explode(':',$parameter);

            if(sizeof($argument)==1 && !isset($controller))
            {
                $controller = $argument[0];
            }
            elseif(array_key_exists($argument[0],$available))
            {
                $arguments[$available[$argument[0]]] = $argument[1];
            }
        }
        if(isset($controller))
        {
            $names = $this->_names($controller);
            $class_name = $names['class'];
            $file_name = $names['file'];
            $directories = $names['directories'];
            
            if(file_exists(APPPATH.'controllers/'.$file_name.'.php'))
            {
                echo $this->_ret.$class_name.' Controller already exists in the application/controllers'.$directories.' directory.';
	            echo "\n";
	            echo "Do you want delete replace it ? (y/n)";
	            $line = fgets(STDIN);
	            if(trim($line) != 'y')
	            {
	                echo "Aborting!".$this->_ret;
	                exit;
	            }
	            echo "\n";
	            echo "creating...DONE".$this->_ret2;
            	$this->cController($arguments,$file_name,$class_name,$directories);
            }
            else
            {
                $this->cController($arguments,$file_name,$class_name,$directories);
            }
        }
        else
        {
            echo $this->_ret.'You need to provide a name for the controller.';
        }
    }

    public function create_model()
    {
    	$available  = array('extend'=>'extend','e'=>'extend');
        $params     = func_get_args();
        $arguments  = array();
        foreach($params as $parameter)
        {
            $argument = explode(':',$parameter);
            if(sizeof($argument)==1 && !isset($model))
            {
                $model = $argument[0];
            }
            elseif(array_key_exists($argument[0],$available))
            {
                $arguments[$available[$argument[0]]] = $argument[1];
            }
        }
        if(isset($model))
        {
            $names = $this->_names($model);
            $class_name = $names['class']."model";
            $file_name = $names['file']."model";
            $directories = $names['directories'];
            if(file_exists(APPPATH.'models/'.$file_name.'.php'))
            {
                echo $this->_ret.$class_name.' Model already exists in the application/models'.$directories.' directory.';
                echo "\n";
	            echo "Do you want delete replace it ? (y/n)";
	            $line = fgets(STDIN);
	            if(trim($line) != 'y')
	            {
	                echo "Aborting!".$this->_ret;
	                exit;
	            }
	            echo "\n";
	            echo "creating...DONE".$this->_ret2;
               	$this->cModel($arguments,$file_name,$class_name,$directories);
            }
            else
            {
               $this->cModel($arguments,$file_name,$class_name,$directories);
            }
        }
        else
        {
            echo $this->_ret.'You need to provide a name for the model.';
        }
    }


    public function create_view($view = NULL)
    {
        $available = array();
        $params = func_get_args();
        $arguments = array();
        foreach($params as $parameter)
        {
            $argument = explode(':',$parameter);
            if(sizeof($argument)==1 && !isset($view))
            {
                $view = $argument[0];
            }
            elseif(array_key_exists($argument[0],$available))
            {
                $arguments[$available[$argument[0]]] = $argument[1];
            }
        }
        if(isset($view))
        {
            $names 			= $this->_names($view);
            $file_name 		= strtolower($names['file']);
            $directories 	= $names['directories'];
            if(file_exists(APPPATH.'views/'.$file_name.'.php'))
            {
                echo $this->_ret.$file_name.' View already exists in the application/views/'.$directories.' directory.';
            }
            else
            {
                $this->cView($file_name,$directories);
            }
        }
        else
        {
            echo $this->_ret.'You need to provide a name for the view file.';
        }
    }




    //////////////////////////////////
    ////  begin local funcs pack  ///
    ////////////////////////////////

    private function isHMVC()
    {
        $check = is_file(APPPATH."core/MY_Loader.php");
        $res   = "";
        
        if ($check === FALSE) 
        {
            $res = "MVC";
        }
        else
        {
            $res = "HMVC";
        }
        return $res;
    }

    private function _getMaster($type)
    {
        $template_loc = $this->_templateLoc.$type.'_template.txt';
        
        if(file_exists($template_loc))
        {
            $f = file_get_contents($template_loc);
            return $f;
        }
        else
        {
            echo $this->_ret."Couldn't find ".$type." template. \n";
            return FALSE;
        }
    }

    private function fileLoader($name='' , $source = '')
    {
        $create = file_put_contents($name, file_get_contents($source) );
        return $create;
    }

    private function loadZipGitHub($nameDest = '',$src = ''){
        $ch = curl_init();
        $source = $src; // THE FILE URL
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec ($ch);
        $destination = $nameDest;// NEW FILE LOCATION
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);
    }

    private function _names($str)
    {
        $str = strtolower($str);
        if(strpos($str,'.'))
        {
            $structure = explode('.', $str);
            $class_name = array_pop($structure);
        }
        else
        {
            $structure = array();
            $class_name = $str;
        }
        $class_name = ucfirst($class_name);
        $file_name = $class_name;
        if (substr(CI_VERSION, 0, 1) != '2')
        {
            $file_name = ucfirst($file_name);
        }
        $directories = implode('/',$structure);
        $file = $directories.'/'.$file_name;
        return array('file'=>$file, 'class'=>$class_name,'directories'=>$directories);
    }

    private function cController($arguments = null,$file_name = null,$class_name = null,$directories = null)
    {
		$f = $this->_getMaster('controller');
        if($f === FALSE) return FALSE;

        $this->_replace['{{CONTROLLER}}'] = $class_name;
        $this->_replace['{{CONTROLLER_FILE}}'] = $file_name.'.php';
        $this->_replace['{{MV}}'] = strtolower($class_name);
        $extends = array_key_exists('extend',$arguments) ? $arguments['extend'] : $this->_c_extends;
        $extends = in_array(strtolower($extends),array('my','ci')) ? strtoupper($extends) : ucfirst($extends);
        $this->_replace['{{C_EXTENDS}}'] = $extends;
        
        $f = strtr($f,$this->_replace);
        if(strlen($directories)>0 && !file_exists(APPPATH.'controllers/'.$directories))
        {
            mkdir(APPPATH.'controllers/'.$directories, 0777, true);
        }
        if(file_put_contents(APPPATH.'controllers/'.$file_name.'.php',$f))
        {
            echo $this->_ret."Controller ".$class_name." has been created inside ".APPPATH."controllers/".$directories.".\n";
            return TRUE;
        }
        else
        {
            echo $this->_ret.'Couldn\'t write Controller.';
            return FALSE;
        }
    }

    public function cModel($arguments = null,$file_name = null,$class_name = null,$directories= null)
    {
 		$f = $this->_getMaster('model');
        if($f === FALSE) return FALSE;
        $this->_replace['{{MODEL}}'] = $class_name;
        $this->_replace['{{MODEL_FILE}}'] = $file_name.'.php';

        $extends = array_key_exists('extend',$arguments) ? $arguments['extend'] : $this->_mo_extends;
        $extends = in_array(strtolower($extends),array('my','ci')) ? strtoupper($extends) : ucfirst($extends);

        $this->_replace['{{MO_EXTENDS}}'] = $extends;
        $f = strtr($f,$this->_replace);
        if(strlen($directories)>0 && !file_exists(APPPATH.'models/'.$directories))
        {
            mkdir(APPPATH.'models/'.$directories, 0777, true);
        }
        if(file_put_contents(APPPATH.'models/'.$file_name.'.php',$f))
        {
            echo $this->_ret.'Model '.$class_name.' has been created inside '.APPPATH.'models/'.$directories.'.';
            return TRUE;
        }
        else
        {
            echo $this->_ret.'Couldn\'t write Model.';
            return FALSE;
        }
    }

    public function cView($file_name = null,$directories = null)
    {
 		$f = $this->_getMaster('view');
 		if($f === FALSE) return FALSE;
        $this->_replace['{{VIEW}}'] = str_replace("/", "", $file_name);

        $f 		= strtr($f,$this->_replace);
    	$dir 	= mkdir(APPPATH.'views/'.$file_name,0777,true);
    	file_put_contents(APPPATH.'views/'.$file_name."/index.php", $f);
    	file_put_contents(APPPATH.'views/'.$file_name."/add.php", $f);
    	file_put_contents(APPPATH.'views/'.$file_name."/edit.php", $f);
    }

    //////////////////////////////////
    ////  end local funcs pack  ///
    ////////////////////////////////



}

/* End of file Cow.php */
/* Location: ./application/controllers/Cow.php */