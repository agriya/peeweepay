<?php
class CronsController extends AppController
{
    var $name = 'Crons';
    function update_product_status()
    {
        $this->autoRender = false;
        App::import('Component', 'cron');
        $this->Cron = &new CronComponent();
        $this->Cron->update_product_status();
    }
}
?>