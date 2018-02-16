<?php
class Zend_View_Helper_Menu extends Zend_View_Helper_Abstract {
	/**
	 * THIS CLASS WILL BE CALLED FROM SIDE NAVIGATION PHTML
	 */
	public function menu() 
	{
		$menu = array(            
            'employee' => array (
                'id' => 'employee',
                'link' => 'buttons',
                'icons' => 'icon-group',
                'label' => 'Employees',
                'href' => '/employee/index',
                'subtitle' => 'Manage Your Staff' ,
                'addnewbuttontitle' => 'Add New Employee'
            ),
            'bin' => array (
                'id' => 'bin',
                'link' => 'buttons',
                'icons' => 'icon-trash',
                'label' => 'Destruction Bins',
                'href' => '/bin/index',
                'subtitle' => 'Manage Your Bins',
                'addnewbuttontitle' => 'Add New Destruction Bin'
            ),
            'checkin' => array (
                'id' => 'checkin',
                'link' => 'buttons',
                'icons' => 'icon-envelope',
                'label' => 'Checked in',
                'href' => '/checkin/list',
                'subtitle' => 'Items checked in' ,
            ),
            'process' => array (
                'id' => 'process',
                'link' => 'buttons',
                'icons' => 'icon-external-link',
                'label' => 'Processed',
                'href' => '/process/list',
                'subtitle' => 'Items processed' ,
            ),
            'onhold' => array (
                'id' => 'onhold',
                'link' => 'buttons',
                'icons' => 'icon-pause',
                'label' => 'On Hold',
                'href' => '/checkin/onhold',
                'subtitle' => 'Items On Hold' ,
            ),
            'reject' => array (
                'id' => 'reject',
                'link' => 'buttons',
                'icons' => 'icon-thumbs-down',
                'label' => 'Rejected',
                'href' => '/checkin/reject',
                'subtitle' => 'Items Rejected' ,
            ),
            'mws' => array (
                'id' => 'mws',
                'link' => 'buttons',
                'icons' => 'icon-refresh',
                'label' => 'Scan in Items',
                'href' => '/mws/scan',
                'subtitle' => ''
            ),
            'products' => array (
                'id' => 'products',
                'link' => 'buttons',
                'icons' => 'icon-list',
                'label' => 'Processing Costs',
                'href' => '/products',
                'subtitle' => 'Manage the processing cost of recovery items'
            ),
            'checked_in' => array (
                'id' => 'checked_in',
                'link' => 'buttons',
                'icons' => 'icon-check',
                'subtitle' => '',
                'label' => 'Checked-in Que',
                'href' => '/mws/checkedin'
            ),
            'report' => array (
                'id' => 'report',
                'link' => 'buttons',
                'icons' => 'icon-file',
                'label' => 'Processing Report',
                'href' => '/mws/report',
            ),            
            'signout' => array (
                'id' => 'signout',
                'link' => 'buttons',
                'icons' => 'icon-off',
                'label' => 'Logout',
                'href' => '/index/signout',
                'subtitle' => 'Sign Out'
            )
        ); 
        if ($_SESSION['vendor'] === 'PRISTINE') {
            unset($menu['mws']);
            unset($menu['products']);
            unset($menu['report']);
            unset($menu['checked_in']);
        } elseif ($_SESSION['vendor'] === 'MWS') {
        	unset($menu['checkin']);
            unset($menu['process']);
            unset($menu['onhold']);
            unset($menu['reject']);
            unset($menu['employee']);
            unset($menu['bin']);
        }

        return $menu;
	}
	public function specificmenu($current) {
		$menus = $this->menu ();
		if (isset ( $current, $menus )) {
			if (is_array($current) && isset($current['menu']))
				$ret = $menus [$current['menu']];
			else
				$ret = $menus [$current];
			return $ret;
		}
		return '';
	}
}
?> 