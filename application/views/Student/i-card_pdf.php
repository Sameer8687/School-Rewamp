 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Student Identity Card</title>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
     <style>
         .label .hero-image .col-md-8 {
             font-size: 10px !important;
             padding: 2px !important;
             color: #476f3d !important;
         }

         .main-box {
             display: flex;
             flex-direction: row;
             justify-content: center;
             width: 85mm;
             height: 54mm;
             border: 1px solid #77777750;
         }

         .hero-image {
             background-image: url("images/bg-design.png");
             background-color: #cccccc;
             background-position: center;
             background-repeat: no-repeat;
             background-size: cover;
             position: relative;
             border: 1px solid #77777750;
         }

         .box {
             border: 1px solid #77777750;
         }

     </style>
 </head>

 <body>
     <div style="width: 100%;">
         <?php $i = 1;
            foreach ($list as $record) {
                if ($i % 2 == 0) {
                    $float = 'float: right';
                } else {
                    $float = 'float: left';
                } ?>
             <div style="width: 50%; <?= $float ?>; display: inline-block; ">
                 <div class="main-box hero-image" style="margin:auto; margin-bottom: 15px; <?= $float ?>;">
                     <hr style="margin-top: 45px; margin-bottom: 5px;">
                     <table>
                         <tr>
                             <td><label for=""><img src="<?= ('stu_img_icard/' . $record['stvicard_stu_img']) ?>" width="20%" style="margin-left: 10px; margin-right: 20px;" class="img-fluid box" alt=""> </label></td>
                             <td> </td>
                             <td> </td>
                             <td>
                                   <table style="font-size: 12px; padding: 5px; text-align: left;">
                                     <tr>
                                         <td> <label for="" class="col-md-4">Stud Id </label></td>
                                         <td style="padding-right: 5px;"><span class="float-right" >:</span> </td>
                                         <td><label for="" class="col-md-8"> <?= $record['SPRIDEN_student_id'] ?></label></td>
                                     </tr>
                                     <tr>
                                         <td><label for="" class="col-md-4">Name </label></td>
                                         <td><span class="float-right">:</span> </td>
                                         <td><label for="" class="col-md-8"> <?= $record['name'] ?></label></td>
                                     </tr>
                                     <tr>
                                         <td><label for="" class="col-md-4 ">Father </label></td>
                                         <td><span class="float-right">:</span> </td>
                                         <td><label for="" class="col-md-8"> <?= $record['father_name'] ?></label></td>
                                     </tr>
                                     <tr>
                                         <td><label for="" class="col-md-4">Class</label></td>
                                         <td> <span class="float-right">:</span> </td>
                                         <td><label for="" class="col-md-8"> <?= $record['STVCLAS_standard'] ?> </label></td>
                                     </tr>
                                     <tr>
                                         <td><label for="" class="col-md-4">Mobile </label></td>
                                         <td><span class="float-right">:</span> </td>
                                         <td><label for="" class="col-md-8"> <?= $record['stvpcnf_mobile'] ?></label></td>
                                     </tr>
                                 </table>
                             </td>
                         </tr>
                     </table>
                 </div>
             </div>
         <?php $i++;
            } ?>
     </div>
 </body>

 </html>