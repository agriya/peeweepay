<?php
class Page extends AppModel
{
    var $name = 'Page';
    var $displayField = 'title';
    var $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'title'
            ) ,
            'overwrite' => false
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'title' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'content' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        $this->statusOptions = array(
            '0' => 'Published',
            '1' => 'Draft'
        );
    }
    /**
     * Find possible parents of a page for select box
     *
     * @deprecated: Use Cake's TreeBehavior::genera...
     * @param int $skipId id to skip
     */
    function getListThreaded($skipId = null, $alias = 'title')
    {
        $parentPages = $this->findAll(null, null, "{$this->name}.lft ASC", null, 1, 0);
        // Array for form::select
        $selectBoxData = array();
        $skipLeft = false;
        $skipRight = false;
        if (empty($parentPages)) return $selectBoxData;
        $rightNodes = array();
        foreach($parentPages as $key => $page) {
            $level = 0;
            // Check if we should remove a node from the stack
            while (!empty($rightNodes) && ($rightNodes[count($rightNodes) -1] < $page[$this->name]['rght'])) {
                array_pop($rightNodes);
            }
            $level = count($rightNodes);
            $dashes = '';
            if ($level > 0) {
                $dashes = str_repeat('&nbsp;', $level) . '-';
            }
            if ($skipId == $page[$this->name]['id']) {
                $skipLeft = $page[$this->name]['lft'];
                $skipRight = $page[$this->name]['rght'];
            } else {
                if (!($skipLeft && $skipRight && $page[$this->name]['lft'] > $skipLeft && $page[$this->name]['rght'] < $skipRight)) {
                    $alias = $page[$this->name]['title'];
                    //$alias = hsc($page[$this->name]['title']);
                    if (!empty($dashes)) $alias = "$dashes $alias";
                    $selectBoxData[$page[$this->name]['id']] = $alias;
                }
            }
            $rightNodes[] = $page[$this->name]['rght'];
        }
        return $selectBoxData;
    }
}
?>