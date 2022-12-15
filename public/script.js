$.validator.setDefaults({
    highlight: function(element) {
        $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function(element) {
        $(element).addClass("is-valid").removeClass("is-invalid");
    },

    //add
    errorElement: 'span',
    errorClass: 'text-danger',
    errorPlacement: function(error, element) {
        if(element.parent('.form-control').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
    // end add
});

let validator = $('.form-validate').validate();

$('#addEmployeeForm').submit(function(e) {
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
                let trElement = '<tr>';
                trElement = trElement + '<th><span class="badge badge-success">new</span></th>';
                trElement = trElement + '<td>'+ res.eid +'</td>';
                trElement = trElement + '<td>'+ res.name +'</td>';
                trElement = trElement + '<td>'+ res.email +'</td>';
                trElement = trElement + '<td><div><span class="badge badge-secondary">'+ res.country +'</span>'+ res.prefix + res.number +'</td>';
                trElement = trElement + '<td>'+ res.designation +'</td>';
                trElement = trElement + '<td>'+ res.department +'</td>';
                trElement = trElement + '<td><a href="#" class="btn btn-sm btn-primary">Edit</a><button type="button" class="btn btn-sm btn-danger">Delete</button></td>';
                trElement = trElement + '</tr>';

                $('#employeeTable').find('tbody').prepend(trElement);
                // validator.resetForm();
                $('#addEmployeeForm').trigger('reset');
                $('#addEmployeeForm').find('.form-group').find('input').removeClass('is-valid');
                $("#addEmployee .close").click();
            },
            error: function(err) {
                console.log(err);
            }
        })
    }
});

let rows = $('#employeeTable tbody tr');
$('#searchEmployee').keyup(function() {
    let val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    rows.show().filter(function() {
        let text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});