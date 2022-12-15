<?php include_once('partials/header.php') ?>
<?php require_once('misc/common-helper.php') ?>
<?php require_once('db/db.php') ?>
<?php $db = new DB() ?>

<div class="row">
    <div class="col-md-6">
        <input type="text" class="form-control" id="searchEmployee" placeholder="Search...">
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#addEmployee">Add Employee</button>
    </div>
    <div class="col-md-12 mt-2">
        <table class="table table-striped" id="employeeTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Employee ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Department</th>
                    <th style="display: none;" scope="col">Created At</th>
                    <th style="display: none;" scope="col">Updated At</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $result = $db->fetchAll($db->query("
                    SELECT emp.*, pb.prefix, pb.number, pb.name as country FROM all_employees emp 
                        LEFT JOIN all_phone_book_links pbl ON emp.id = pbl.table_id AND pbl.table_name = 'all_employees'
                        LEFT JOIN all_phone_book pb ON pbl.phone_book_id = pb.id 
                    ORDER BY emp.id DESC
                ")); ?>
                <?php foreach ($result as $index => $employee) { ?>
                    <tr id="et-trow<?= $employee['id'] ?>">
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $employee['eid'] ?></td>
                        <td><?= $employee['name'] ?></td>
                        <td><?= $employee['email'] ?></td>
                        <td>
                            <div><span class="badge badge-secondary"><?= $employee['country'] ?? '' ?></span><?= ($employee['prefix'] ?? '') . ($employee['number'] ?? '') ?></div>
                        </td>
                        <td><?= $employee['designation'] ?></td>
                        <td><?= $employee['department'] ?></td>
                        <td style="display: none;"><?= date('F j, Y g:i:a', strtotime($employee['created_at'])) ?></td>
                        <td style="display: none;"><?= date('F j, Y g:i:a', strtotime($employee['updated_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editEmployee<?= $employee['id'] ?>">Edit</button>
                            <form class="form-inline" action="controller/employee-controller.php" method="post" id="deleteEmployeeForm<?= $employee['id'] ?>">
                                <input type="hidden" name="form_type" value="delete">
                                <input type="hidden" name="id" value="<?= $employee['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            
                            <?php customInclude('partials/modal-start.php', [
                                'id' => 'editEmployee' . $employee['id'],
                                'title' => 'Edit Employee('. $employee['eid'] .')',
                                'isForm' => true,
                                'formId' => 'editEmployeeForm' . $employee['id'],
                                'formAction' => 'controller/employee-controller.php'
                            ]) ?>
                            <input type="hidden" name="form_type" value="edit">
                            <input type="hidden" name="id" value="<?= $employee['id'] ?>">
                            <div class="form-group">
                                <input type="number" name="eid" class="form-control" placeholder="Employee ID" value="<?= $employee['eid'] ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Employee Email" value="<?= $employee['email'] ?>" required>
                            </div>
                            <div class="form-group">
                                <!-- pattern="[-+]?\d*" -->
                                <input type="text" name="phone" class="form-control" placeholder="Employee Phone" value="<?= $employee['prefix'] . $employee['number'] ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Employee Name" value="<?= $employee['name'] ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="designation" class="form-control" placeholder="Employee Designation" value="<?= $employee['designation'] ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="department" class="form-control" placeholder="Employee Department" value="<?= $employee['department'] ?>" required>
                            </div>

                            <?php customInclude('partials/modal-end.php', ['isForm' => true]) ?>
                            
                            <script>
                                $('#editEmployeeForm<?= $employee['id'] ?>').submit(function(e) {
                                    e.preventDefault();

                                    if ($(this).valid()) {
                                        $.ajax({
                                            type: $(this).attr('method'),
                                            url: $(this).attr('action'),
                                            data: $(this).serializeArray(),

                                            beforeSend: function() {
                                                // do something (e.g. show loader)
                                            },
                                            success: function(res) {
                                                res = JSON.parse(res);
                                                // let trElement = '<tr>';
                                                let trElement = '';
                                                trElement = trElement + '<th><span class="badge badge-warning">modified</span></th>';
                                                trElement = trElement + '<td>'+ res.eid +'</td>';
                                                trElement = trElement + '<td>'+ res.name +'</td>';
                                                trElement = trElement + '<td>'+ res.email +'</td>';
                                                trElement = trElement + '<td><div><span class="badge badge-secondary">'+ res.country +'</span>'+ res.prefix + res.number +'</td>';
                                                trElement = trElement + '<td>'+ res.designation +'</td>';
                                                trElement = trElement + '<td>'+ res.department +'</td>';
                                                trElement = trElement + '<td><a href="#" class="btn btn-sm btn-primary">Edit</a><button type="button" class="btn btn-sm btn-danger">Delete</button></td>';
                                                // trElement = trElement + '</tr>';

                                                $('#employeeTable').find('#et-trow<?= $employee['id'] ?>').html(trElement);
                                                // validator.resetForm();
                                                $("#editEmployee<?= $employee['id'] ?>").find('.close').click();
                                                $('body').removeClass('modal-open');
                                                $('.modal-backdrop').remove();
                                            },
                                            error: function(err) {
                                                console.log(err);
                                            }
                                        })
                                    }
                                });

                                $('#deleteEmployeeForm<?= $employee['id'] ?>').submit(function(e) {
                                    e.preventDefault();

                                    if ($(this).valid()) {
                                        $.ajax({
                                            type: $(this).attr('method'),
                                            url: $(this).attr('action'),
                                            data: $(this).serializeArray(),

                                            beforeSend: function() {
                                                // do something (e.g. show loader)
                                            },
                                            success: function(res) {
                                                res = JSON.parse(res);
                                                if (res) {
                                                    $('#employeeTable').find('#et-trow<?= $employee['id'] ?>').remove();
                                                }
                                            },
                                            error: function(err) {
                                                console.log(err);
                                            }
                                        })
                                    }
                                });
                            </script>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modals -->
<?php customInclude('partials/modal-start.php', [
    'id' => 'addEmployee',
    'title' => 'Add Employee',
    'isForm' => true,
    'formId' => 'addEmployeeForm',
    'formAction' => 'controller/employee-controller.php'
]) ?>
<input type="hidden" name="form_type" value="add">
<div class="form-group">
    <input type="number" name="eid" class="form-control" placeholder="Employee ID" required>
</div>
<div class="form-group">
    <input type="email" name="email" class="form-control" placeholder="Employee Email" required>
</div>
<div class="form-group">
    <!-- pattern="[-+]?\d*" -->
    <input type="text" name="phone" class="form-control" placeholder="Employee Phone" required>
</div>
<div class="form-group">
    <input type="text" name="name" class="form-control" placeholder="Employee Name" required>
</div>
<div class="form-group">
    <input type="text" name="designation" class="form-control" placeholder="Employee Designation" required>
</div>
<div class="form-group">
    <input type="text" name="department" class="form-control" placeholder="Employee Department" required>
</div>

<?php customInclude('partials/modal-end.php', ['isForm' => true]) ?>

<?php $db->close() ?>
<?php include_once('partials/footer.php') ?>