<?php
Class FacebookStream{
    
    public function __construct(){
        $this->appID = get_option('facebook_stream_appID');
        $this->pageID = get_option('facebook_stream_fbPageID');
        $this->appSecret = get_option('facebook_stream_appSecret');
    }
    
    public function setFBPageID($fbpage){
        $this->pageID = $fbpage;
    }
    
    /**
     * Curl get content - https enabled
     * @param type $fullurl
     * @return type
     */
    public function GetHTTPSData($fullurl) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $fullurl);
        $returned = curl_exec($ch);

        return $returned;
    }
    
    public function trimText($text, $max) {
        if (strlen($text)<=$max){
            return $text;
        }
        return substr($text, 0, $max-3).'...';
    }
    
    /**
     * Genrate token
     * @return type
     */
    public function GenerateAccessToken(){
        $fullurl = "https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=$this->appID&client_secret=$this->appSecret";
        $ret = $this->GetHTTPSData($fullurl);
        return $ret;
    }
    
    /**
     * Explode string on multiple roles
     * @param type $delimiters
     * @param type $string
     * @return type
     */
    public function multiexplode ($delimiters,$string) {
    
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    } 
    
    
 
    /**
     * Get all fb items
     * @param type $api_url
     * @return boolean
     */
    public function GetFbPageItems($api_url){
        
        $data = $this->GetHTTPSData($api_url);
        $obj = json_decode($data);        

        // parse and prepare data from api
        if($obj->data){
            
            // genrate array
            foreach ($obj->data as $element) {
                
                // prepare picture
                if(isset($element->attachments->data[0]->media->image->src)){
                $post_image = $element->attachments->data[0]->media->image->src;
                } else {
                    if(isset($element->full_picture)){
                        $post_image = $element->full_picture;
                    } else {
                        $post_image = (isset($element->picture))? $element->picture : null;
                    }
                }
                
                $mydata['id'] = (isset($element->id)) ? $element->id : null;
                $mydata['created_time'] = (isset($element->created_time)) ? $element->created_time : null;
                $mydata['picture'] = ($post_image) ? $post_image : null;
                $mydata['link'] = (isset($element->link)) ? $element->link : null;
                $mydata['from'] = (isset($element->from->name)) ? $element->from->name : null; 
                $mydata['from_id'] = (isset($element->from->id)) ? $element->from->id : null; 
                $mydata['name'] = (isset($element->name)) ? $element->name : null; 
                $mydata['caption'] = (isset($element->caption)) ? $element->caption : null;
                $mydata['icon'] = (isset($element->icon)) ? $element->icon : null;
                $mydata['type'] = (isset($element->type)) ? $element->type: null;
                $mydata['message'] = (isset($element->message)) ? $element->message : null;
                $mydata['source'] = (isset($element->source)) ? $element->source : null; 
                $mydata['likes_count'] = (isset($element->likes->summary->total_count)) ? $element->likes->summary->total_count : "0";
                $mydata['comments_count'] = (isset($element->comments->summary->total_count)) ? $element->comments->summary->total_count : "0";
                $mydata['fb_link'] = ($element->type === 'photo' && isset($element->link)) ? $element->link : 'https://facebook.com/'.$element->id;
                $FBPosts[$element->id] = $mydata;
            }

            
            if($obj->paging->next){
                $next_page_url = $obj->paging->next;
            } else{
                $next_page_url = false;
            }
            
            return array("data"=>$FBPosts,"next_url"=>$next_page_url);
        } else {
            return false;
        }
    }
    
    /**
     * Get items from fb page
     * @param int $limit
     * @param string $token
     * @param string $fields
     * @return type
     */
    public function GetListOfFBPosts($only_owners_posts,$limit,$token,$fields = 'id,created_time,full_picture,picture,link,name,caption,icon,type,message,source,likes.limit(1).summary(true),comments.limit(1).summary(true),from,attachments'){
        
        // set number of pages
        if(!$limit){
            $number_of_pages = 1;
        } else {
            $number_of_pages = round($limit/25);
            if($number_of_pages < 1){
                $number_of_pages = 1;
            }
        }
        // generate first url
        $page_url = "https://graph.facebook.com/v2.2/$this->pageID/feed?limit=$limit&$token&fields=$fields";

        // set page counter
        $i = 0;
        $FBPage = null;
        $Items = array();
        do{
            if($i == $number_of_pages){
                break;
            }
            
            $page_url_enter = ($FBPage['next_url']) ? $FBPage['next_url'] : $page_url;
            $FBPage = $this->getFBPageItems($page_url_enter);
            // check is it array
            if(is_array($FBPage['data']) && is_array($Items)){
                $Items = array_merge($Items,$FBPage['data']);
            } else{
                break;
            }
            $i++;
        } while($FBPage['next_url']);
        
        // get group ID
        if($only_owners_posts){
            $submited_page_data = $this->GetHTTPSData('https://graph.facebook.com/v2.2/'.$this->pageID.'/?'.$token);

            $submited_page_json = json_decode($submited_page_data);
            $submited_page_id = $submited_page_json->id;
            // remove from array if it's not posted by owner
            foreach($Items as $key=>$oneItem){
                if($oneItem['from_id'] !== $submited_page_id){
                    unset($Items[$key]);
                }
            }
        }
        
        return $Items;
    }
    
    
        
    /**
     * Get username from facebook
     * @param type $userid
     * @return type
     */
    public function FBUsername($userid){
        $file = $this->GetHTTPSData("http://graph.facebook.com/$userid");
        $obj = json_decode($file);
        $username = (isset($obj->username)) ? $obj->username : null;
        return $username;
    }
    
    /**
     * Generate time from wp string
     * @param type $created_time
     * @return type
     */
    private function GenerateTimeFromString($created_time){
        $date = explode("T", $created_time);
        $d = $date[0];
        $t = explode("+", $date[1]);
        $t = $t[0];
        $dt = "$d $t";
        return $dt;
    }
    
    
}

