<table id="list" class="table table-striped table-bordered nowrap">




<tr>

    <th>Sr.No.</th>

    <th>Driver Name</th>

    <th>Mobile No</th>

    <th>Whatsapp No</th>

    <th>Address</th>

    <th>Mobile No 2</th>

    <th>Vehicle</th>

    

</tr>


<?php if (!empty($rows)) : ?>
<?php foreach ($rows as $row) : ?>
<tr>
    <td><?php echo $row['Driver_name'] ?></td>
    <td><?php echo $row['Mobile_no'] ?></td>
    <td><?php echo $row['Whatsapp_no'] ?></td>
    <td><?php echo $row['mobile'] ?></td>
    <td><?php echo $row['Vehicle'] ?></td>
    <!-- <td><?php echo $row[''] ?></td> -->
    <?php endforeach; ?>

    <?php endif ?>
</tr>


</table>