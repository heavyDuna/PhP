<?php
echo "<form action = '' method = 'get'>
        Validar palabra: 
        <input type='text' name='word'><br><br>
        <input type = 'submit' name = 'env' value = 'Enviar'><br>
    </form>";
if(isset($_GET['word']) && trim($_GET['word']) != null){
    $word = $_GET['word'];
    $spellcheck = speller($word);
    echo $spellcheck;
}
function speller($word){
    $words_list = [];
    $find_words = getWords(\Slim\Slim::getInstance()->db, $word);
    var_dump($find_words);
    if(in_array($word, $find_words) == false && empty($find_words)){
        foreach ($find_words as $result){
            similar_text($word, $result['pais'],$percent);
            if($percent > 82){
                $words_list = $result['pais'];
            }
        }
    }else{
        $words_list = 'No hay sugerencias o la palabra es correcta';
    }
    return $words_list;
}
function getWords(BaseDatos $bd, $word)
{
    $word = substr($word, 0, 1);
    $consulta = "SELECT pais FROM paises WHERE LEFT(pais, 1) = '$word'";
    $expresion = $bd->prepare($consulta);
    $expresion->execute();
    return $expresion->fetchAll(PDO::FETCH_ASSOC);
}
