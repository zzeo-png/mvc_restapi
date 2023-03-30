<?php
/*
  Enostaven primer controlletja, ki ne uporablja modela.
  Njegova naloga je, da vrača statične HTML strani, kot je stran z napako.
  Uporabili smo ga tudi za prikaz vmesnika, ki demonstrira uporabi API-ja.
*/

class pages_controller {
  public function error() {
    // Izpiše pogled s sporočilom o napaki
    require_once('views/pages/error.php');
  }

  public function api(){
    // Izpiše pogled, ki demonstrira uporabo API-ja
    require_once('views/pages/api.php');
  }
}
?>