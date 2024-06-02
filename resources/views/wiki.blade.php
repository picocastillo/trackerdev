@extends('layouts.app')
@section('content')
    <div class="container">

            <div id="wiki" >
              <div class="alert alert-warning mt-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="alert-heading">Importante</h4>
                  <p>Es de vital importancia que comprendas cada linea, el copiar, pegar y ejecutar sin entender solo llevará a sofisticados problemas. Si no se encuentra un entendimiento investigando, PREGUNTAR.</p>
                  <hr>
                  <p class="mb-0">Estos son algunos enlaces útiles <a href="https://laravel.com/docs/7.x/installation" class="alert-link">#Laravel, </a>
                    <a href="https://linuxize.com/post/how-to-install-mysql-on-ubuntu-18-04/" class="alert-link">#Mysql, </a>
                    <a href="https://www.techrepublic.com/article/how-to-install-phpmyadmin-on-ubuntu-18-04/" class="alert-link">#Phpmyadmin,</a>
                    <a href="https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04" class="alert-link">#LAMP</a>
                    .</p>
              </div>
                <div class="card card-body mt-2">
                    
                      
                      
                    
                      <div class="col-md-12 wiki ">
         
                        <i>*** LAMP</i></br>
                        <span>sudo apt update</span></br>
                        <span>sudo apt install apache2</span></br>
            
                        <span>sudo apt install mysql-server</span></br>
                        {{-- <span>##sudo mysql_secure_installation</span></br> --}}
            
                        <span>sudo apt install php libapache2-mod-php</span></br>
                        <span>sudo systemctl restart apache2</span></br>
                        <i># to can show localhost/info.php</i></br>
                        <i> #sudo echo &quot;&lt; ?php&quot;&gt;/var/www/html/info.php && sudo echo &quot;phpinfo();&quot; &gt;&gt; /var/www/html/info.php</i></br>
            
                        <span>sudo apt install php-mysql</span></br>
            
            
                        <span>sudo apt install php-dev libmcrypt-dev php-pear</span></br>
                        <span>sudo pecl channel-update pecl.php.net</span></br>
                        <i># added in sudo nano /etc/php/7.2/cli/php.ini</i></br>
                        <span>sudo apt-get update</span></br>
            
                        <span>sudo apt-get install -y phpmyadmin</span></br>
            
            
                        <span>sudo service apache2 restart</span></br>
            
                        <span>#added in sudo -H gedit /etc/apache2/apache2.conf this Include /etc/phpmyadmin/apache.conf and restart with /etc/init.d/apache2 restart</span></br>
                        <i>***if you have problem with password</i></br>
                        <span>#update mysql.user set authentication_string=password(&apos;YOUR_PASS&apos;) where user=&quot;root&quot;;</span></br>
                        <span>#to check users SELECT user,authentication_string,plugin,host FROM mysql.user;</span></br>
            
            
                        <span>#laravel</span></br>
                        <span>##First, check if you have all dependencies</span></br>
                        <span>##php -m | grep ctype</span></br>
                        <span>## php -m | grep bcmath</span></br>
                        <span>## php -m | grep fileinfo</span></br>
                        <span>## php -m | grep json</span></br>
                        <span>## php -m | grep mbstring</span></br>
                        <span>## php -m | grep openssl</span></br>
                        <span>## php -m | grep tokenizer</span></br>
                        <span>## php -m | grep xml</span></br>
                        <span>## if it is not insstal with sudo apt install php-bcmath for example</span></br>
                        <span>#composer</span></br>
                        <span>sudo apt update</span></br>
                        <span>sudo apt install wget php-cli php-zip unzip</span></br>
                        <span>cd ~</span></br>
                        <span>curl -sS https://getcomposer.org/installer -o composer-setup.php</span></br>
                        <span>#ADD hash in enviroment like HASH (https://composer.github.io/pubkeys.html) (export HASH=...) and run</span></br>
                        <span>php -r &quot;if (hash_file(&apos;SHA384&apos;, &apos;composer-setup.php&apos;) === &apos;$HASH&apos;) { echo &apos;Installer verified&apos;; } else { echo &apos;Installer corrupt&apos;; unlink(&apos;composer-setup.php&apos;); } echo PHP_EOL;&quot;</span></br>
                        <span>#If it is verified, run</span></br>
                        <span>sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer</span></br>
            
                        <span>sudo composer global require laravel/installer</span></br>
            
                        <span>#generate ssh </span></br>
                        <span>ssh-keygen</span></br>
                        <span>#To view it cat ~/.ssh/id_rsa.pub </span></br>
                    </div>
                </div>
            </div>
            <div  id="checking" >
                <div class="alert alert-warning mt-2" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading">Importante</h4>
                    <p>Es de vital importancia que comprendas cada linea, en caso contrario PREGUNTAR. El objetivo de esta lista es evitar descuidos una vez que se considera que se completó una tarea.</p>
                    <hr>
                    <p class="mb-0">A la hora de dejar notas en las tareas se debe leer lo que se escribe, de manera de expresar correctamente la duda o dificultad encontrada. Consulta este enlace muy útil de  <a target="_blank" href="https://es.stackoverflow.com/help/how-to-ask" class="alert-link"> Stackoverflow</a>
                      .</p>
                </div>
                <div class="card card-body  mt-2">
                    <div>
                        <div class="row">
                        <div class="card p-3  col-md-8">
                            <h2>Checking de Testing</h2>
                            <li>¿Está el código bien Indentado?</li>
                            <li>¿Está todo el código transparente al usuario final en Inglés?</li>
                            <li>¿Existe código innesario comentado?</li>
                            <li>¿Existe algunos Debuggers que no fueron eliminados?</li>
                            <li>¿En caso de haber hecho un desarrollo de vista, se probó en distintas resoluciones y exploradores( chrome y mozila como mínimo), resoluciones desde la de 320 px hasta la más grande posible?</li>
                            <li>¿Lo que hice, no genero un error o bug? ¿Que partes puede influenciar mi desarrollo?</li>
                            <li>Las funcionalidades pedidas, ¿funcionan correctamente y están completas?¿hay tratamiento de errores?</li>
                            <li>En caso de un usuario malintencionado, ¿podría romper mi solución? ¿Tuve en cuenta todos los casos?</li>
                            <li>¿Respeto el estilo de programación del proyecto? nombre de variables, funciones, Camelcase, etc</li>
                            <li>¿Se agrego codigo js o css en el lugar correcto ?</li>
                            <li>¿Hice una inspección de mi codigo ? Deberia revisar el código que escribi para asegurarme que no tienen errores, tanto de compilación, logicos, de estilos de programación, etc.</li>
                            <li>¿No genero ningun error en el error_log de Laravel?</li>
                            <li>¿No genero ningun error js en la consola del explorador?</li>
                            <li>¿Si es un trabajo de vista, no se genera un scroll indeseado ?</li>
                            <li>¿Se hizo uso del la herramienta <b>git difftool</b> para verificar el código comiteado?</li>
                            <li>Deje una última nota con el branch en el que trabajé ? </li>
                            <li>En la última nota, deje especificaciones de como probar la tarea, sugerencias a tener en cuenta, detalles necesarios de advertir en el desarrollo?</li>
                            <li>En caso de haber involucrado inputs en mi tarea, ¿ hago validaciones tanto del lado del servidor como del cliente?</li>
                            <li>En caso de que mi tarea sea parte de un proyecto con diferentes roles, ¿Probé dicha funcionalidad para todos los roles?</li>
                            <li>Me han quedado estilos inline? Es decir, los estilos css siempre van en la hoja de estilos, NUNCA en el tag HTML</li>
                            <li>Si me pongo del lado  del usuario, es mi solución congruente, entendible y práctica? Se tiene una buena experiencia de usuario? </li>
                            <li>En caos de hacer consultas a la DB, estoy hciendo siendo óptimo del motor con mi Query? Se podria mejorar? Tengo dudas? Estoy paginando en caso de ser una respuesta grande? </li>
                        </div>
                        <div class="card p-3 col-md-4">
                            <h2>Analisis de causas</h2>
                            <li> ¿Por qué, según tu punto de vista (recursivamente hasta donde pueda), sucedió esto?</li>
                            <li>¿Como crees qué se podria evitar en un futuro? En caso de creer que se pudo haber evitado</li>
                            <li>¿Existe algún aprendizaje ganado en esto?</li>
                            <li>¿Hice una inspección de código?¿Analicé la posibilidad de iteraciones innecesarias?¿Revicé que mi codigo no se pueda mejorar?</li>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div  id="get-started" >
                <div class="card card-body mt-2">
                    <div class="col-md-12 ">
                        <li><i>Cómo compilar los css?</i></li>
                         <b>npm run dev </b>  
                        <p>Comando para compilar automáticamente, detectando cambios:</p>
                        <b>npm run watch</b>
                        <li><i>Migración el Laravel y seeders</i></li><br/>
                        <b>php artisan migrate: fresh --seed</b>
                        <li><i>Creando migracion</i></li><br/>
                        <b>php artisan make:migration create_users_table </b> por ejemplo, para crear una tabla llamada users
                        <li><i>Creando migracion y modelo</i></li><br/>
                        <b>php artisan make:model Blog -m </b>
                        <li><i>Consala Tinker</i></li><br/>
                        <b>php artisan tinker</b>
                        <h4>Problemas comunes</h4>
                        <li><i>Cuando corro npm run watch me sale un error</i></li>
                         <b>npm install</b>
                        <li><i>No me levanta el proyecto con php artisan serve</i></li>
                         <b>composer update</b>
                      </div>
                </div>
            </div>



      <div class="row card card-body">
        <div class="col-md-6 ">
          <li><i>Cómo compilar los css?</i></li>
           <b>npm run dev </b>  
          <p>Comando para compilar automáticamente, detectando cambios:</p>
          <b>npm run watch</b>
          <li><i>Migración el Laravel y seeders</i></li><br/>
          <b>php artisan migrate: fresh --seed</b>
          <li><i>Creando migracion</i></li><br/>
          <b>php artisan make:migration create_users_table </b> por ejemplo, para crear una tabla llamada users
          <li><i>Creando migracion y modelo</i></li><br/>
          <b>php artisan make:model Blog -m </b>
          <li><i>Consala Tinker</i></li><br/>
          <b>php artisan tinker</b>
          <h4>Problemas comunes</h4>
          <li><i>Cuando corro npm run watch me sale un error</i></li>
           <b>npm install</b>
          <li><i>No me levanta el proyecto con php artisan serve</i></li>
           <b>composer update</b>
        </div>
        <div class="col-md-12  ">
         
            <i>#LAMP</i></br>
            <span>sudo apt update</span></br>
            <span>sudo apt install apache2</span></br>

            <span>sudo apt install mysql-server</span></br>
            <span>##sudo mysql_secure_installation</span></br>

            <span>sudo apt install php libapache2-mod-php</span></br>
            <span>sudo systemctl restart apache2</span></br>
            <span># to can show localhost/info.php</span></br>
            
            <span> #sudo echo ">/var/www/html/info.php && sudo echo "phpinfo();" >> /var/www/html/info.php</span></br>

            <span>sudo apt install php-mysql</span></br>


            <span>sudo apt install php-dev libmcrypt-dev php-pear</span></br>
            <span>sudo pecl channel-update pecl.php.net</span></br>
            <span># added in sudo nano /etc/php/7.2/cli/php.ini</span></br>
            <span>sudo apt-get update</span></br>

            <span>sudo apt-get install -y phpmyadmin</span></br>


            <span>sudo service apache2 restart</span></br>

            <span>#added in sudo -H gedit /etc/apache2/apache2.conf this Include /etc/phpmyadmin/apache.conf and restart with /etc/init.d/apache2 restart</span></br>
            <span>#if you have problem with password</span></br>
            <span>#update mysql.user set authentication_string=password('YOUR_PASS') where user="root";</span></br>
            <span>#to check users SELECT user,authentication_string,plugin,host FROM mysql.user;</span></br>


            <span>#laravel</span></br>
            <span>##First, check if you have all dependencies</span></br>
            <span>##php -m | grep ctype</span></br>
            <span>## php -m | grep bcmath</span></br>
            <span>## php -m | grep fileinfo</span></br>
            <span>## php -m | grep json</span></br>
            <span>## php -m | grep mbstring</span></br>
            <span>## php -m | grep openssl</span></br>
            <span>## php -m | grep tokenizer</span></br>
            <span>## php -m | grep xml</span></br>
            <span>## if it isn't insstal with sudo apt install php-bcmath for example</span></br>
            <span>#composer</span></br>
            <span>sudo apt update</span></br>
            <span>sudo apt install wget php-cli php-zip unzip</span></br>
            <span>cd ~</span></br>
            <span>curl -sS https://getcomposer.org/installer -o composer-setup.php</span></br>
            <span>#ADD hash in enviroment like HASH (https://composer.github.io/pubkeys.html) (export HASH=...) and run</span></br>
            <span>php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"</span></br>
            <span>#If it is verified, run</span></br>
            <span>sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer</span></br>

            <span>sudo composer global require laravel/installer</span></br>

            <span>#generate ssh </span></br>
            <span>ssh-keygen</span></br>
            <span>#To view it cat ~/.ssh/id_rsa.pub </span></br>
        </div>
       
      </div>
    </div>


    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

@endsection
