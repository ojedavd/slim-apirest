<?php

//Cargamos el framework
require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

//Creamos la conexión a la base de datos con MySQLi
$db = new mysqli('localhost', 'root', '', 'pruebas');

/*
 * Ruta/Controlador Get, le decimos que use las variables $db, $app
 * GET para CONSEGUIR
 */
$app->get('/users', function () use($db, $app) {

            $select = $db->query("select * from users;");
            $users=array();
            while($fila=$select->fetch_assoc()){
                $users[]=$fila;
            }

            echo json_encode($users);
        });

//POST para INSERTAR
$app->post('/users', function () use($db, $app) {
            //Request recoge variables de las peticiones http
            $request = $app->request;
			
            $insert = $db->query("INSERT INTO users VALUES(NULL,
                                                   '{$request->post("email")}',
                                                   '{$request->post("password")}',
                                                   '{$request->post("nombre")}',
                                                   '{$request->post("apellido")}'
                                                   )");
            if ($insert) {
                $result = array("status" => "true", "message" => "Usuario creado correctamente");
            } else {
                $result = array("status" => "false", "message" => "Usuario NO creado");
            }
            echo json_encode($result);
        });

//PUT para ACTUALIZAR
$app->put('/users/:id', function ($id) use($db, $app) {

            $request = $app->request;

            $sql = "UPDATE users SET
                                    email = '{$request->params("email")}',
                                    password = '{$request->params("password")}',
                                    nombre = '{$request->params("nombre")}',
                                    apellido = '{$request->params("apellido")}'
                                 WHERE id=$id";

            $update = $db->query($sql);

            if ($update) {
                $result = array("status" => "true", "message" => "Usuario modificado correctamente");
            } else {
                $result = array("status" => "false", "message" => "Usuario NO modificado");
            }
            echo json_encode($result);
        });

//DELETE para BORRAR
$app->delete('/users/:id', function ($id) use($db, $app) {

            $request = $app->request;

            $sql = "DELETE FROM users WHERE id=$id";

            $delete = $db->query($sql);

            if ($delete) {
                $result = array("status" => "true", "message" => "Usuario eliminado correctamente");
            } else {
                $result = array("status" => "false", "message" => "Usuario NO eliminado");
            }
            echo json_encode($result);
        });

$app->run();
?>