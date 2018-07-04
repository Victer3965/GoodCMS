<?php

require_once 'classDataTable.php';

class MenuDataSource extends DataTable {

    protected $menuName;
    
    public static function fieldMap() {
        return parent::fieldMap() + [
            'cmp:menuName' => 'menuName'
        ];
    }

    public function getData(){
        //echo '<!--getData-->';
        $this->query = 'SELECT id,parent_id,`order`,`text`,link,new_tab
FROM gc_menus_items AS i
LEFT JOIN gc_menus_items_text AS t ON
    i.menu_id=(SELECT id FROM gc_menus WHERE name=' . $this->provider->quote($this->menuName) . ')
    AND t.item_id=i.id
    AND t.locale=' . $this->provider->quote(
            //ServerData::CurrentUser()->locale
            'ru_RU'
            ) . ' ORDER BY parent_id, `order`';
        return parent::getData();
        
    }

    public function render(&$stream) {
    }

}
