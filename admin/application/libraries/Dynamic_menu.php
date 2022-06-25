<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Dynmic_menu.php
 */
class Dynamic_menu {
 
    private $ci;            // para CodeIgniter Super Global Referencias o variables globales
    private $id_menu        = 'id="menu"';
    //private $class_menu        = 'class="sidebar-menu"';
    private $class_menu        = 'class="stream stream0"';

    private $class_parent    = 'class="treeview-menu"';
    private $class_last        = 'class="last"';
     private $class_name        = 'class="sidebar-menu"';
    // --------------------------------------------------------------------
    /**
     * PHP5        Constructor
     *
     */
    function __construct()
    {
        $this->ci =& get_instance();    // get a reference to CodeIgniter.
    }
    // --------------------------------------------------------------------
     /**
     * build_menu($table, $type)
     *
     * Description:
     *
     * builds the Dynaminc dropdown menu
     * $table allows for passing in a MySQL table name for different menu tables.
     * $type is for the type of menu to display ie; topmenu, mainmenu, sidebar menu
     * or a footer menu.
     *
     * @param    string    the MySQL database table name.
     * @param    string    the type of menu to display.
     * @return    string    $html_out using CodeIgniter achor tags.
     */
 
    function build_menu($type, $params='')
    {
        $menu = array();
        $classMenu = $this->class_menu;
        if(!empty($params))
            $classMenu = $params;
        /*echo '<pre>';
        print_r($classMenu);exit;*/
        /*echo "select * from dyn_menu where dyn_group_id='".$type."'";
        exit;*/
        $query = $this->ci->db->query("select * from dyn_menu where dyn_group_id='".$type."' order by position asc");
      /*  $query = $this->ci->db->query("select * from dyn_menu where type=$dyn_group_id ");*/
        /*echo '<pre>';
        print_r($query->result_array());
        exit;*/
        // now we will build the dynamic menus.
        //$html_out  = "\t".'<div '.$this->id_menu.'>'."\n";
        $html_out  = "";
 
        /**
         * check $type for the type of menu to display.
         *
         * ( 0 = top menu ) ( 1 = horizontal ) ( 2 = vertical ) ( 3 = footer menu ).
         */
         $html_out .= "\t\t".'<ul '.$classMenu.'>'."\n";
       /* switch ($type)
        {
            case 0:            // 0 = top menu
                $html_out .= "\t\t".'<ul '.$classMenu.'>'."\n";
                break;
 
            case 1:            // 1 = horizontal menu
                $html_out .= "\t\t".'<ul '.$classMenu.'>'."\n";
                break;
 
            case 2:            // 2 = sidebar menu
                break;
 
            case 3:            // 3 = footer menu
                break;
 
            default:        // default = horizontal menu
                $html_out .= "\t\t".'<ul '.$classMenu.'>'."\n";
 
                break;
        }*/
 
    // me despliega del query los rows de la base de datos que deseo utilizar
      foreach ($query->result() as $row)
            {
                $id = $row->id;
                $title = $row->title;
                $link_type = $row->link_type;
                $page_id = $row->page_id;
                $module_name = $row->module_name;
                $url = $row->url;
                $uri = $row->uri;
                $dyn_group_id = $row->dyn_group_id;
                $position = $row->position;
                $target = $row->target;
                $parent_id = $row->parent_id;
                $is_parent = $row->is_parent;
                $show_menu = $row->show_menu;
                $class = $row->anchor_attribute;
                $li_class = $row->li_attribute;
                $icon_type = $row->icon_type;
                $icon = $row->icon;
                $dividerDiv = $row->divider;
                $is_tab = $row->is_tab;
                $childAttribute = !empty($row->child_attribute)?$row->child_attribute:$this->class_parent;
                //$html_tags_id = $row->html_tags_id;
 
              {
                if ($show_menu && $parent_id == 0)   // are we allowed to see this menu?
 
                {
                   /* echo '<pre>';
                    print_r($this->class_parent);
                    exit;*/
                    /*$title2.= '<span>'.$title.'</span><span class="stream stream0"><i class="fa fa-angle-left pull-right"></i></span>';*/
                    $title2 = !empty($icon)?'<i class="'.$icon.'"></i>':'';
                    if($icon_type=='Image'){
                      $title2.=img('assets/uploads/icons/');
                    }

                    if ($is_parent == TRUE)
                    {
                        $title2.= '<span>'.$title.'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
                    // CodeIgniter's anchor(uri segments, text, attributes) tag.
                        if($url=="#"){
                          $html_out .= "\t\t\t".'<li><a href="#" title="'.$title.'" id="'.$id.'" target="'.$target.'" class="'.$class.'">'.$title2.'<span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span></a>';
                        }else
                        $html_out .= "\t\t\t".'<li>'.anchor($url, $title2, 'name="'.$title.'" id="'.$id.'" target="'.$target.'" class="'.$class.'"');
 
 /*$html_out .= "\t\t\t".'<li>'.anchor($url.' '.$this->class_name, '<span>'.$title.'</span>', 'name="'.$title.'" id="'.$id.'" target="'.$target.'"');*/
                    }
                    else
                    {
                        $title2.= '<span>'.$title.'</span>';
                        $html_out .= "\t\t\t".'<li>'.anchor($url, $title2, 'name="'.$title.'" id="'.$id.'" target="'.$target.'" class="'.$class.'"');
                    }
 
               }
                /* echo '<pre>';
                    print_r($title2);exit;*/
            }
           
            $dynamicMenuTagQuery = $this->ci->db->query("select * from dyn_menu_tags where dyn_menu_id='".$id."' and is_active=1 order by priority");
            if(count($dynamicMenuTagQuery->result_array())>0){ 
              //print_r($dynamicMenuTagQuery->result_array());exit;
                foreach ($dynamicMenuTagQuery->result_array() as $dynamicMenuTag) {
                    $html_out.='<'.$dynamicMenuTag['tag'].' '.$dynamicMenuTag['attribute'].'>';
                    $html_out .= $this->get_childs($id, $childAttribute);
                    $html_out.='</'.$dynamicMenuTag['tag'].'>';

                }
            }else{ 
                $html_out .= $this->get_childs($id, $childAttribute);
            }
          // print_r($id);
        }
         // loop through and build all the child submenus.
 
        $html_out .= '</li>'."\n";
        $html_out .= "\t\t".'</ul>' . "\n";
        //$html_out .= "\t".'</div>' . "\n";
 
        return $html_out;
    }
     /**
     * get_childs($menu, $parent_id) - SEE Above Method.
     *
     * Description:
     *
     * Builds all child submenus using a recurse method call.
     *
     * @param    mixed    $id
     * @param    string    $id usuario
     * @return    mixed    $html_out if has subcats else FALSE
     */
    function get_childs($id, $class_parent = '')
    {
        $has_subcats = FALSE;
 
        $html_out  = '';
        $tab_out = '';
        //$html_out .= "\n\t\t\t\t".'<div>'."\n";
 
        // query q me ejecuta el submenu filtrando por usuario y para buscar el submenu segun el id que traigo
        //echo "select * from dyn_menu where parent_id = $id";
        $query = $this->ci->db->query("select * from dyn_menu where parent_id = $id");
         /*echo "<pre>";
         print_r($query->result_array());exit;*/
        if(count($query->result())>0)
            $html_out .= "\t\t\t\t\t".'<ul '.$class_parent.'>'."\n";
        else
            $html_out .= "\t\t\t\t\t".'<ul>'."\n";

        foreach ($query->result() as $row)
        {
            $id = $row->id;
            $title = $row->title;
            $link_type = $row->link_type;
            $page_id = $row->page_id;
            $module_name = $row->module_name;
            $url = $row->url;
            $uri = $row->uri;
            $dyn_group_id = $row->dyn_group_id;
            $position = $row->position;
            $target = $row->target;
            $parent_id = $row->parent_id;
            $is_parent = $row->is_parent;
            $show_menu = $row->show_menu;
            $anchorAttribute = $row->anchor_attribute;
            $liAttribute = $row->li_attribute;
            $icon = $row->icon;
            $dividerDiv = $row->divider;
            $is_tab = $row->is_tab;
            $childAttribute = !empty($row->child_attribute)?$row->child_attribute:$this->class_parent;
            $has_subcats = TRUE;
             
            $title2 = !empty($icon)?'<i class="'.$icon.'"></i>':'';
            if($is_tab){ 
                $liAttribute .= 'data-id='.$id.' data-target="menu-'.$id.'" id="tabm_'.$id.'"'; 
                $tab_out .= '<div class="menu_cont clearfix" data-id="'.$id.'" style="display: block;" id="menu-'.$id.'"></div>';
                //$tab_out .= '<div class="col-sm-4"></div>';
            }
            if ($is_parent == TRUE)
            {
                $title2.= '<span>'.$title.'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
                /* $title2.= '<span>'.$title.'</span><span class="stream stream0"><i class="fa fa-angle-left pull-right"></i></span>';*/
                /*$html_out .= "\t\t\t\t\t\t".'<li>'.anchor($url.' '.$this->class_parent, '<span>'.$title.'</span>', 'name="'.$title.'" id="'.$id.'" target="'.$target.'"');*/
                $html_out .= "\t\t\t\t\t\t".'<li '.$liAttribute.'>'.anchor($url, $title2, $anchorAttribute);

            }
            else
            {
                $title2.= '<span>'.$title.'</span>';
                $html_out .= "\t\t\t\t\t\t".'<li '.$liAttribute.'>'.anchor($url, $title2, $anchorAttribute);
            }

            // Recurse call to get more child submenus.
            if($dividerDiv){
                $html_out.='<div class="divider" style="display:block"></div>';
            }
            $dynamicMenuTagQuery = $this->ci->db->query("select * from dyn_menu_tags where dyn_menu_id='".$id."' and is_active=1 order by priority");
            /*echo "<pre>";
            print_r($dynamicMenuTagQuery->result_array());*/
           /* echo "select * from dyn_menu_tags where dyn_menu_id='".$id."' and is_active=1 order by priority <br>";*/
            if(count($dynamicMenuTagQuery->result_array())>0){ 
            //echo "hello"; 
                //echo $this->ci->db->count_all_results();
               /* echo '<pre>';
                print_r($dynamicMenuTagQuery->result_array());*/
               // exit;
                foreach ($dynamicMenuTagQuery->result_array() as $dynamicMenuTag) {
                    $html_out.='<'.$dynamicMenuTag['tag'].' '.$dynamicMenuTag['attribute'].'>';
                    /*echo $dynamicMenuTag;exit;*/
                    /*if($is_tab== TRUE){
                       // echo "hello";
                    $html_out .= $$this->get_Tabs($id);
                    }else{*/
                        $html_out .= $this->get_childs($id, $childAttribute);
                   // }
                    $html_out.='</'.$dynamicMenuTag['tag'].'>';

                }
            }else{ //echo "hiii";exit;
                /*if($is_tab == TRUE) {
                    //echo "reached in tabs";
                    $html_out .=$this->get_Tabs($id);
                }else
                {
*/
                $html_out .= $this->get_childs($id, $childAttribute);
                //}
            }
        }
        $html_out .= '</li>' . "\n";
        $html_out .= "\t\t\t\t\t".'</ul>' . "\n";
        $html_out .=$tab_out;
        /*$query1 = $this->ci->db->query("select html_tags_id from dyn_menu");
        $query1->result_array();*/
        //$html_out .= "\t\t\t\t".'</div>' . "\n";
 
        return ($has_subcats) ? $html_out : FALSE;
 
    }


    function parentCategory_menu($params=''){
      //$query = "Select * from product_categories where parent_id=0";
      $query = $this->ci->db->query("Select * from product_categories where parent_id=0");
      //$parentCategories = ;
      $classMenu = $this->class_menu;
        if(!empty($params))
            $classMenu = $params;

      $html_out  = "";
      if(count($query->result_array())>0){
   
        $html_out .= "\t\t".'<ul '.$classMenu.'>'."\n";
        //echo '<pre>';
        //print_r($parentCategories);
        foreach ($query->result_array() as $key => $parentCategory) {
          $html_out .='<li>';
          $childCategory = $this->childCategory_menu($parentCategory['id'], 'class="treeview-menu"');
          //print_r($childCategory);exit;
          if($childCategory != ''){ //echo "hii";
            $html_out .= anchor('product-category/'.$parentCategory['slug'], $parentCategory['category_name']);
            $html_out .= $childCategory;
          }else{ //echo "hello";
            $html_out .= anchor('product-list/'.$parentCategory['slug'], $parentCategory['category_name']);
          }
          $html_out .='<li>'."\n";
        }

        //$html_out .= '</li>'."\n";
        $html_out .= "\t\t".'</ul>' . "\n";
          //$html_out .= "\t".'</div>' . "\n";
   
        return $html_out;
      }

      return $html_out;
      //exit; 

    }

    function childCategory_menu($id, $params=''){
      $query = $this->ci->db->query("Select * from product_categories where parent_id=".$id);
      $html_out = '';
      //$parentCategories = ;
      
      $classMenu = $this->class_menu;
        if(!empty($params)){
          $classMenu = $params;
        }
      if(count($query->result_array())>0){

        $html_out  = "";
        $html_out .= "\t\t".'<ul '.$classMenu.'>'."\n";
        
        

        //echo '<pre>';
        //print_r($parentCategories);
        foreach ($query->result_array() as $key => $parentCategory) {
          $html_out.='<li>';
          $childCategory = $this->childCategory_menu($parentCategory['id'], 'class="treeview-menu"');
          if($childCategory != ''){ //echo "hii";
            $html_out .= anchor('product-category/'.$parentCategory['slug'], $parentCategory['category_name']);
            $html_out .= $childCategory;
          }else{ //echo "hello";
            $html_out .= anchor('product-list/'.$parentCategory['slug'], $parentCategory['category_name']);
          }


          $html_out.='</li>'. "\n";
        }

        //$html_out .= '</li>'."\n";
        $html_out .= "\t\t".'</ul>' . "\n";
          //$html_out .= "\t".'</div>' . "\n";
   
      }
      return $html_out;
    }
}
 
// ------------------------------------------------------------------------
// End of Dynamic_menu Library Class.
// ------------------------------------------------------------------------
/* End of file Dynamic_menu.php */
/* Location: ../application/libraries/Dynamic_menu.php */