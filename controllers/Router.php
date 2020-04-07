<?php
class Router 
{
    private $_ctrl;
    private $_view;

    public function routeReq()
    {
        try
        {
            // CHARGEMENT AUTOMATIQUE DES CLASSES
            spl_autoload_register(function($class) {
                require_once('models/'.$class.'.php');
            });

            $url = '';

            // ICI EST DEFINI QUEL FICHIER DOIT ÊTRE APPELÉ EM FONCTION DU CHOIX DE L'UTILISATEUR
            if(isset($_GET['url']))
            {
                $url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));

                $controller = ucfirst(strtolower($url[0]));
                $controllerClass = "Controller".$controller;
                $controllerFile = "controllers/".$controllerClass.".php";

                // CONTROLE SI LE FICHIER EXISTE
                if(file_exists($controllerFile))
                {
                    require_once($controllerFile);
                    $this->_ctrl = new $controllerClass($url);
                }
                else {
                    throw new Exception('Page introuvable');
                }
            }
            else {
                require_once('controllers/ControllerAccueil.php');
                $this->_ctrl = new ControllerAccueil($url);
            }
        }
        // GESTION DES ERREURS
        catch
        {
            $errorMsg = $e->getMessage();
            require_once('views/viewError.php');
        }
    }
}