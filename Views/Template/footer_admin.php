<?php $company = getCompanyInfo();?>
          
        
          </div>
          </div>
          <!-- Content End -->
          <!-- Back to Top -->
          <!--<a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a> -->       
        </div>

        <!------------------------------Admin template--------------------------------->
        
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/lib/chart/chart.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/lib/easing/easing.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/lib/waypoints/waypoints.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/lib/tempusdominus/js/moment.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/lib/tempusdominus/js/moment-timezone.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
        <script src="<?=media()?>/DashboardTemplate/js/main.js"></script>
        
        <!------------------------------Plugins--------------------------------->
        <script src="<?= media();?>/plugins/sweetalert/sweetalert.js"></script>

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>

        <script src="<?= media();?>/plugins/datepicker/jquery-ui.min.js"></script>
        <script src="<?= media();?>/plugins/sheetjs/sheetjs.js"></script>
        <script src="<?= media();?>/plugins/print/print.min.js"></script>
        <script src="<?= media();?>/plugins/datatables/datatables.min.js"></script>
        <script src="<?= media();?>/plugins/datatables/jszip.min.js"></script>
        <!------------------------------My functions--------------------------------->

        <script>
          const base_url = "<?= base_url(); ?>";
          const MS = "<?=$company['currency']['symbol'];?>";
          const MD = "<?=$company['currency']['code']?>";
        </script>
        
        <script type="text/javascript" src="<?= media(); ?>/js/functions.js"></script>
        
        <?php if(isset($data['panelapp'])){?>
        <script src="<?=media();?>/js/<?=$data['panelapp']."?v=".rand()?>"></script>
        <?php }?>
        <?php if(isset($data['framing'])){?>
        <script src="<?=media();?>/js/<?=$data['framing']."?v=".rand()?>"></script>
        <?php }?>
    </body>
</html>
    
