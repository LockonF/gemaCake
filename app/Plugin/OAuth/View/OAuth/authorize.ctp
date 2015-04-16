
    <header class="container-fluid">
        <h2>
            <img src="../img/logo.png" class="logo-mini">
            GEMA APIs
        </h2>
        <h1>Autorización: </h1><h3>Es necesaria para que la aplicación pueda utilizar el API</h3>

    </header>
    <div class="container-fluid">
        <div class="row">
            <h3 class="text-center">¿Autorizas a la aplicación para que utilice tus datos personales?</h3>
        </div>



        <?php
        echo $this->Form->create('Authorize');
        foreach ($OAuthParams as $key => $value) {
            echo $this->Form->hidden(h($key), array('value' => h($value)));
        }
        ?>


    <div class="row">
        <div class="col-md-4 col-md-offset-2 col-sm-12">
            <button name="accept" type="submit" class="btn btn-default btn-lg btn-block" value="Si">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Acepto
            </button>
        </div>
        <div class="col-md-4 col-sm-12">
            <button name="accept" type="submit" class="btn btn-default btn-lg btn-block" value="No">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> No Acepto
            </button>
        </div>
    </div>

        <div class="row">
            <div class="col-md-12 panel panel-default" role="alert">
                <h4 class="text-danger text-center">En GEMA no tenemos control sobre lo que la aplicación de terceros pueda hacer
                    con tus datos personales, así que autoriza solamente a aplicaciones de confianza</h4>
            </div>
        </div>

    </div>
