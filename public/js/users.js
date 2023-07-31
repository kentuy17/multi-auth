const TYPE = {
  pending: 'PENDING',
  completed: 'COMPLETED',
  failed: 'FAILED'
}

var usersTable = $('#admin-users-table');
var onlineCount = 0;

usersTable.DataTable({
  "ajax": '/admin/users',
  "bPaginate": true,
  "bLengthChange": false,
  "bFilter": true,
  "bInfo": false,
  "bAutoWidth": true,
  "scrollX": true,
  "serverSide": true,
  "processing": true,
  "order": [[5, 'desc']],
  "columnDefs": [
    {
      "targets": [4],
      "className": 'dt-body-right',
    },
  ],
  "columns": [
    {
      className: 'dt-control dt-body-left',
      orderable: false,
      data: "id",
      defaultContent: ''
    },
    {
      "data": "username"
    },
    {
      "data": "phone_no"
    },
    {
      "data": null,
      render: (data) => {
        let roles = '';
        data.roles.forEach((x) => {
          roles +=`<label class="badge bg-success mr-1">${x.name}</label>`
        })
        return roles;
      }
    },
    {
      "data": null,
      render: (data) => {
        return data.points.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
      },
    },
    {
      "data": "status"
    },
    {
      "data": "last_activity"
    },
    {
      "data": null,
      render: (data) => {
          return `<a href="javascript:void(0)" data-id="${data.id}" class="btn btn-link text-primary btn-icon btn-sm view">
          <i class="fa-solid fa-circle-info"></i></a>`;
      }
    },
  ],
  "createdRow": function( row, data, dataIndex){
    $(row).find('td').eq(0).attr('style', 'color: transparent !important');

    if( data.active){
      $(row).addClass('table-success');
      onlineCount++;
    }

    if(data.roles.length > 4) {
      let rolesCol = $(row).find('td').eq(3);
      rolesCol.addClass('flex flex-wrap gap-1');
    }
  },
  "drawCallback": function (settings) {
    let response = settings.json;
    $('#badge-online-users').show().text(response.online_count);
  },
});

function formatDeposit(d) {
  let points = d.points.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  let action = `<a href="/admin/access/${d.id}" target="_BLANK" class="btn btn-link text-primary btn-icon" style="padding-left:0;">
    <i class="fa-solid fa-lock-open fa-lg"></i>`;
  return (
    `<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
      <tr>
        <td>ID:</td>
        <td>#${d.id}</td>
      </tr>
      <tr>
        <td>PLAYER:</td>
        <td>${d.username}</td>
      </tr>
      <tr>
        <td>PHONE#:</td>
        <td>${d.phone_no}</td>
      </tr>
      <tr>
        <td>POINTS:</td>
        <td>${points}</td>
      </tr>
      <tr>
        <td>ACCESS:</td>
        <td>${action}</td>
      </tr>
    </table>`
  );
}

$('#admin-users-table tbody').on('click', 'td.dt-control', function () {
  var tr = $(this).closest('tr');
  var row = usersTable.DataTable().row(tr);

  if (row.child.isShown()) {
    row.child.hide();
    tr.removeClass('shown');
  }
  else {
    row.child(formatDeposit(row.data())).show();
    tr.addClass('shown');
  }
});

usersTable.on('click', 'tbody td .view', async function() {
  clearFields();
  var tr = $(this).closest('tr');
  var row = usersTable.DataTable().row(tr);
  var id = $(this).data('id');
  $('#modal-center').modal('show')
  $('.modal-title').text(row.data().username)
  $('input#user_id').val($(this).data('id'));
  $('input#username').val(row.data().username);
  $('input#phone_no').val(row.data().phone_no);
  $('.page-access').each((index, el) => $(el).prop('checked',false))

  getUserPermissions(id)
    .then((permissions) => {
      let perms = [];
      let user = permissions.data.user;
      permissions.data.data.forEach((p) => {
        perms.push(p.role_id);
      })
      $('input#name').val(user.id==666?user.username:user.name);
      $('select#role').val(user.role_id);
      return perms;
    })
    .then((perms) => {
      for (let i = 0; i < perms.length; i++) {
        const el = perms[i];
        $('#page_access_'+el).prop('checked',true);
      }
    })

  let storage = $('#trans-receipt').data('storage');
  if(row.data().filename) {
    $('#trans-receipt').attr('src', storage+'/'+row.data().filename);
  }

  // if(row.data().status != 'pending') {
  //   $('input[type="submit"]').prop('disabled', true)
  //     .addClass('disabled');
  // } else {
  //   $('input[type="submit"]').prop('disabled', false)
  //     .removeClass('disabled');
  // }
})

function clearFields() {
  $('#trans-pts').val(''), $('#ref-code').val(''), $('#trans-note').val(''),
    $('#trans-action').val('approve'), $('#trans-note').parent().hide();
}

$('[data-dismiss="modal"]').on('click', function() {
  $('#modal-center').modal('hide');
})

function clearFields() {
  $('#updated-trans-pts').val(''), $('#trans-note-undo').val(''),
  $('#trans-note').parent().hide();
}

$('[data-dismiss="modal"]').on('click', function() {
  $('#modal-undo-points').modal('hide');
})

$('form#user-form').on('submit', function(e) {
  e.preventDefault();
  let serialized = $(this).serialize();
  updateUser(serialized).then((user) => {
    Swal.fire({
      icon: 'success',
      confirmButtonColor: 'green',
      title: user.data.message,
      timer: 1500
    }).then(() =>  {
      $('#modal-center').modal('hide');
      usersTable.DataTable().ajax.reload();
    });
  });
});

async function getUserPermissions(userId) {
  try {
    const response = await axios.get(`/admin/user-permissions/${userId}`);
    return response;
  }
  catch (error) {
    console.log(error);
  }
}

async function updateUser(data) {
  try {
    const user = await axios.post('/admin/user', data);
    // console.log(user);
    return user;
  }
  catch (error) {
    console.log(error.message);
  }

}
