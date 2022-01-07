<?php
/////Clase para Manejo de BD MySQL desde PHP

class OperacionMysql{

   ////funcion Constructora

public function __construct()
{
   GLOBAL $bbdd_host, $bbdd_user, $bbdd_pass, $bbdd_bbdd;    
   global $conectado;

      $this->conexion = mysqli_connect($bbdd_host,$bbdd_user,$bbdd_pass);
          
      mysqli_select_db($this->conexion, $bbdd_bbdd);

      if($this->conexion){

         $conectado = true;

      }else{

         $conectado = false;

      }


}

////Metodo para hacer el query lleva como parametro tu sentencia SQL

   function doQuery($sqlString){

         $this->doQueryS = mysqli_query ($this->conexion, $sqlString);

         if($this->doQueryS){
            return true;
         }else{
            $this->errorNo = mysqli_error($this->conexion);
            echo "Error en la sentencia SQL del metodo <b>doQuery ".$sqlString."</b> <br>Mensaje Del Error:<font color='#FF0000'>".$this->errorNo."</font>";
         }
   }



///---END ------------------------------------------------------------------------------------------------   

////Funcion que nos dice el numero de Campos de la Tabla

   function getNumFields (){

       return $this->NumFields = mysqli_num_fields($this->doQueryS);

   }

///---END ------------------------------------------------------------------------------------------------      

   ///Funcion que nos dice el nombre de un campo en especifico pasando un numero de campo como parametro

   function getNameField($iterador){

      echo $this->fieldNamesArray[$iterador] = mysql_field_name($this->doQueryS,$iterador);

   }

///---END ------------------------------------------------------------------------------------------------   

   ///Funcion que nos dice el nombre de todos los campos separados por coma

   function getNameFieldsAll(){

      $this->NumFields = mysqli_num_fields($this->doQueryS);

      for($iterador=0;$iterador<$this->NumFields;$iterador++){

         echo $this->fieldNamesArray[$iterador] = mysql_field_name($this->doQueryS,$iterador).",";

      }

   }

///---END ------------------------------------------------------------------------------------------------   

   ///Funcion que nos Devuelve el identificador generado en la última llamada a INSERT 

   function getInsertID(){

       return $this->getLastID = mysql_insert_id();

   }

///---END ------------------------------------------------------------------------------------------------   

   

   ///Funcion que nos dice el numero de Registros que se afectaron el la consulta

   function getNumRows(){

       return $this->affectedRows = mysqli_num_rows($this->doQueryS);

   }

///---END ------------------------------------------------------------------------------------------------   

   

   ///Funcion que nos dice el numero de Registros que se afectaron el la consulta

   function getAffectedRows(){

       return $this->affectedRows = mysqli_affected_rows($this->conexion);

   }

///---END ------------------------------------------------------------------------------------------------   

   /////retorna el dataprovider en un array

   function setWhile(){

      $this->setDataProvider = mysqli_fetch_array($this->doQueryS);
      return $this->setDataProvider;

   }

///---END ------------------------------------------------------------------------------------------------   

   ////Funcion que devuelve los registros de un campo en especifico

   function getDataSQL($campoName){

      return $this->setDataProvider[$campoName];

   }

///---END ------------------------------------------------------------------------------------------------   

   ///funcion que libera la memoria

   function setFreeResult(){

      mysql_free_result($this->doQueryS);

   }

///---END ------------------------------------------------------------------------------------------------   

   /////funcion que cierra la conexion con la BD

   function setClose(){

      mysql_close($this->conexion);

   }

///---END ------------------------------------------------------------------------------------------------   

}



///Forma de Implementación------------------------------------------------------------

///Instancia de la clase OperacionMysql;

//$Datos  = new OperacionMysql();

//$Datos ->doQuery("SELECT * FROM noticias_m_bloquse ORDER BY n_bloque_id");

//echo $Datos ->getNumFields();

//$Datos ->getNameField(0);

//$Datos ->getNameFieldsAll();

//echo $Datos ->getNumRows();

//while($Datos ->setWhile()){

//   echo $Datos ->getDataSQL("n_bloque_nombre")."<br>";

///Aqui se puede mandar a llamar al metodo getDataSQL tantas veces como sea necesario

//}

//*/

//echo $Datos ->getAffectedRows();

//$Datos ->setFreeResult();

//$Datos ->setClose();

////--END ----------------------------------------------------------------------

?>