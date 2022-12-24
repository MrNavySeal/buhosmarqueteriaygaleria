<?php
    headerPage($data);
    $company = getCompanyInfo();
    $social = getSocialMedia();

    $links ="";
    for ($i=0; $i < count($social) ; $i++) { 
        if($social[$i]['link']!=""){
            if($social[$i]['name']=="whatsapp"){
                $links.='<li><a href="https://wa.me/'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
            }else{
                $links.='<li><a href="'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
            }
        }
    }
?>
    <div class="container mt-4">
        <section class="mt-4 mb-4">
            <div class="row">
                <div class="col-md-6 mt-4">
                    <div class="p-4">
                        <h2 class="t-color-2 mb-3">Si quieres interactuar con nosotros</h2>
                        <ul>
                            Estaremos encantados de atenderte para:
                            <li class="mt-3">Cualquier ayuda profesional que necesites</li>
                            <li class="mt-3">Sugerencias, recomendaciones o hacer un testimonio sobre nuestro servicio</li>
                            <li class="mt-3">Oportunidades de negocio</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 mt-4">
                    <form class="form--contact p-4" id="formContact">
                        <h2 class="t-color-4">Mándanos un email</h2>
                        <p class="t-color-3">Nos encontramos en <?=$company['addressfull']?></p> 
                        <div class="form--contact-data">
                            <label>¿Cuál es tu nombre?</label>
                            <input type="text" id="txtContactName" name="txtContactName" placeholder="Nombre">
                            <span class="form-focus-effect"></span>
                        </div>
                        <div class="form--contact-data">
                            <label>¿Cuál es tu teléfono?</label>
                            <input type="text" id="txtContactPhone" name="txtContactPhone" placeholder="310 123 1234">
                            <span class="form-focus-effect"></span>
                        </div>
                        <div class="form--contact-data">
                            <label>¿Cuál es tu correo?</label>
                            <input type="text" id="txtContactEmail" name="txtContactEmail" placeholder="micorreo@ejemplo.com">
                            <span class="form-focus-effect"></span>
                        </div>
                        <div class="form--contact-data">
                            <label>Tu mensaje</label>
                            <textarea name="txtContactMessage" id="txtContactMessage" rows="3" placeholder="Escribe tu mensaje"></textarea>
                            <span class="form-focus-effect"></span>
                        </div>
                        <div class="alert alert-danger mt-3 d-none" id="alertContact" role="alert"></div>
                        <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
                            <button type="submit" class="btn btn-bg-1 mb-3" id="btnMessage">Enviar mensaje</button>
                            
                            <ul class="social mb-3">
                                <?=$links?>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
            <div class="map mt-4">
                <iframe class="contact--map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d497.43094086071005!2d-73.62887549945499!3d4.132008249047646!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3e2e72bdc34df1%3A0xd7ff9e6fdd7a5cbb!2sCra.%2036%20%2315a3%2C%20Villavicencio%2C%20Meta!5e0!3m2!1ses!2sco!4v1665440386579!5m2!1ses!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </div>
<?php
    footerPage($data);
?>