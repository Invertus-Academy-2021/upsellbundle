<?php

class AdminProducttopdfConfigurationController extends ModuleAdminController {
 
        public function __construct()
        {
           
            parent::__construct();
            $this->bootstrap = true;
        }
        public function init()
        {
            parent::init();
            $this->initOptions();
        }

        
        
        public function initOptions()
        {
            $this->fields_options = array(
                'training' => array(
                    'title' => $this->module->l('Configuration'),
                    'fields' => array(
                        'SHOW_PRICE' => array(
                            'title' => $this->module->l('Show price'),
                            'cast' => 'intval',
                            'type' => 'bool',
                        ),
                        'SHOW_IMAGE' => array(
                            'title' => $this->module->l('Show image'),
                            'cast' => 'intval',
                            'type' => 'bool',
                        ),
                        'SHOW_FEATURES' => array(
                            'title' => $this->module->l('Show features'),
                            'cast' => 'intval',
                            'type' => 'bool',
                        ),
                        'SHOW_ATTRIBUTES' => array(
                            'title' => $this->module->l('Show attributes'),
                            'cast' => 'intval',
                            'type' => 'bool',
                        )
                    ),
                    'submit' => array('title' => $this->trans('Save', array(), 'Admin.Actions')),
                ),
            );
        }
}