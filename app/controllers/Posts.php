<?php
  class Posts extends Controller{

    public function __construct()
    {
        if (!isLoggedIn()) {
            Redirect('users/login');
        }    

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        // Get posts
        $posts = $this->postModel->getPosts(); 


        $data = [
            'posts' => $posts
        ];
        $this->view('posts/index', $data);   
    } // End function index
    
    public function add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            // validate Title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }

            // validate bod
            if(empty($data['body'])){
                $data['body_err'] = 'Please enter body';
            }

            // Make sure no errors
            if (empty($data['title_err']) && empty($data['body_err']) ) {
                // Validated
                if ($this->postModel->addPost($data)) {
                    flash('post_message', 'Post Added');
                    Redirect('posts');
                } else {
                    die('SomeThing went wrong');
                }
              
            } else {
                // Load View with errors
                $this->view('posts/add', $data);
            }
        } else {
            $data = [
                'title' => '',
                'body' => ''
            ];
            $this->view('posts/add', $data);
        }
        
    } // End function add


    public function show($id)
    {
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);

        $data = [
            'post' => $post,
            'user' => $user
        ];
        $this->view('posts/show',$data);
    } // end function show


    public function edit($id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'id' => $id,
                'title_err' => '',
                'body_err' => ''
            ];

            // validate Title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }

            // validate bod
            if(empty($data['body'])){
                $data['body_err'] = 'Please enter body';
            }

            // Make sure no errors
            if (empty($data['title_err']) && empty($data['body_err']) ) {
                // Validated
                if ($this->postModel->updatePost($data)) {
                    flash('post_message', 'Post Edited');
                    Redirect('posts');
                } else {
                    die('SomeThing went wrong');
                }
              
            } else {
                // Load View with errors
                $this->view('posts/edit', $data);
            }
        } else {
            // Get existing post from model
            $post = $this->postModel->getPostById($id);

            // Check for owner
            if($post->user_id != $_SESSION['user_id']){
                Redirect('posts');
            }

    
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
            ];
            $this->view('posts/edit', $data);
        }
        
    } // End function edit


    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get existing post from model
            $post = $this->postModel->getPostById($id);


            // Check for owner
            if($post->user_id != $_SESSION['user_id']){
                Redirect('posts');
            }

            if ($this->postModel->deletePost($id)) {
                flash('post_message', 'Post Removed');
                Redirect('posts');

            } else {
                die('Something went wrong!');
            }
        } else {
            Redirect('posts');
        }
    } // End function delete 

  }