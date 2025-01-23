<?php
interface GetInfo{
    public function get();
}

interface SendInfo{
    public function process(array $data);
}

class HttpClient implements GetInfo{
    private $url;

    public function __construct($url){
        $this->url = $url;        
    }
 
    public function get(){
        try{
            $response = file_get_contents($this->url);
            if ($response === FALSE){
                throw new Exception("Error al realizar la solicitud HTTP.");
            }

            $data = json_decode($response, true);

            return $data;

        } catch(Exception $e){
            die("Exepción capturada: " . $e->getMessage() . "\n");
        }
    }
}

class ConsoleProcessor implements SendInfo{
    public function process(array $data)
    {
        foreach ($data as $post){
            echo "ID: " .$post['id'] . "\n";
            echo "Título: " .$post['titulo'] . "\n";
            echo "Contenido: " .$post['body'] . "\n";
            echo "-------------------------\n";
        }
    }

}

class FileProcessor implements SendInfo {
    private string $filename;

    public function __construct(string $filename){
        $this->filename = $filename;        
    }
    public function process(array $data){
        $fileContent = "";

        foreach ($data as $post){
            $fileContent .= "ID: " .$post['id'] . "\n";
            $fileContent .= "Título: " .$post['title'] . "\n";
            $fileContent .= "Contenido: " .$post['body'] . "\n";
            $fileContent .= "-------------------------\n";
        }

        if (file_put_contents($this->filename, $fileContent) === FALSE){
            die("Error al guardar la información en el archivo.");
        }

        echo "Información guardada en el archivo: {$this->filename}\n";
    }

}

class HtmlProcessor implements SendInfo {
    private $filename;

    public function __construct($filename){
        $this->filename = $filename;
    }
    
    public function process(array $data)
    {
        $filename = "output.html";
        $htmlContent = "<html><head><title>Datos del Servicio Web</title></head><body>";
        $htmlContent .= "<h1>Datos del Servicio Web</h1>";

        foreach ($data as $post){
            $htmlContent .= "<div style='margin-bottom: 20px;'>";
            $htmlContent .= "<h2>ID: ". $post['id'] . "</h2>";
            $htmlContent .= "<h3>Título: " . htmlspecialchars($post['title']) . "</h3>";
            $htmlContent .= "<p>Contenido: " . nl2br(htmlspecialchars($post['body'])) . "</p>";
            $htmlContent .= "<hr>";
            $htmlContent .= "</div>";
        }

            $htmlContent .= "</body></html>";
        }
    
        

        if (file_put_contents($filename, $htmlContent) === FALSE) {
            die("Error al guargar la información en el archivo HTML\n");
        }
    
        echo "Información guardada en el archivo HTML: $filename\n";

    }
}
## Caso de Uso, Qué es lo que va a hacer, sin necesidad de saber el cómo lo va hacer.
class UseCase {
    private $getInfo;
    private $sendInfo;

    public function __construct(GetInfo $getInfo, SendInfo $sendInfo){
        $this->getInfo = $getInfo;
        $this->sendInfo = $sendInfo;
        
    }

    // public function get(string $url){
    //     try {

    //         $response = file_get_contents($this->$url);
    //         if ($response === FALSE){
    //             throw new Exception("Error al realizar la solicitud HTTP.");
    //         }

    //         $data = json_decode($response, true);

    //         return $data;


    //     }catch (Exception $e){
    //         die("Excepción capturada: " .$e->getMessage() . "\n");
    //     }
    // }

    public function run(){
        $data = $this->getInfo->get();
        $this->sendInfo->process($data);
    }
}

$httpClient = new HttpClient("https://jsonplaceholder.typicode.com/posts");
$op = 3;

switch ($op){
    case 1:
        $processor = new ConsoleProcessor();
        break;
    case 2:
        $processor = new FileProcessor("output.txt");
        break;
    case 3:
        $processor = new HtmlProcessor("output.html");
        break;
    
    default:
        die("Opción no válida. Usa 'mostrar', 'guardar' o 'html' .\n");

}

// $processor = new ConsoleProcessor();

# Para el caso de uso es irrelevante qué es lo que le mandes,
# Simplemente le importa, que le mandes, algo que implemente GetInfo()
$useCase = new UseCase($httpClient, $processor);
$useCase->run();