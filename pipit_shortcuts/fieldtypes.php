<?php
/**
 * A field type for selecting Runway Menus
 * 
 * @author Hussein Al Hammad
 * 
 */
class PerchFieldType_runwaymenulist extends PerchAPI_FieldType
{
    public function render_inputs($details=array())
    {
        if (!PERCH_RUNWAY)
        {
            exit;
        }


        
        $id  = $this->Tag->input_id();
        $val = '';
        
        if (isset($details[$id]) && $details[$id]!='') 
        {
            $val = $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()); 
        }
        

           
        $MenuItems = new PerchMenuItems;
        $menus = $MenuItems->get_top_level();

        $opts   = array();
        $opts[] = array('label'=>'', 'value'=>'');

        if (PerchUtil::count($menus))
        {
            foreach($menus as $Menu)
            {
                $opts[] = array('label' => $Menu->itemTitle(), 'value' => $Menu->itemID());
            }
        }

        
        
        $classes= 'input-simple categories m selectized';
        $attributes = ' data-display-as="categories"';
        
        if ($this->Tag->max()) 
        {
			$attributes .= ' data-max="'.(int)$this->Tag->max().'"';
        }
        
        if ($this->Tag->placeholder()) 
        {
            $attributes .= ' placeholder="'.$this->Tag->placeholder().'"';
        }
        else
        {
            $attributes .= ' placeholder=" "';
        }
        
        $attributes = trim($attributes);

        
        
        if (PerchUtil::count($opts)) 
        {
        	$s = $this->Form->select($id, $opts, $val, $classes, true, $attributes);
        } 
        else 
        {
        	$s = '-';
        }
        

        return $s;
    }
       
    public function get_raw($post=false, $Item=false) 
    {
        $store  = array();
        $id     = $this->Tag->id();

        if ($post===false)
        {
            $post = $_POST;
        }
        
        if (isset($post[$id])) 
        {
            $this->raw_item = $post[$id];
            $menus = $this->raw_item;

            foreach($menus as $menuID)
            {
                $store[] = $menuID;
            }
        }

        return $store;
    }
    
    public function get_processed($raw=false)
    {    
        if ($raw===false)
        {
            $raw = $this->get_raw();
        }
        
        $value = $raw;

        if (is_array($value)) 
        {
            return implode(',', $value);
        }
        
        return $value;
    }
    
    public function get_search_text($raw=false)
    {
		return false;
    }
}