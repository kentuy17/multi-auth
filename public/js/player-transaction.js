$(document).ready(function () {
  // DEPOSIT TABLE
  $('#player-transaction-table').DataTable({
    "bPaginate": true,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": false,
    "bAutoWidth": false,
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, 'desc']],
    "ajax": '/player/transaction/deposit',
    "pageLength": 25,
    "columnDefs": [
      {
        "targets": [2],
        "className": 'dt-body-right',
      },
      {
        "targets": [4, 5],
        "className": 'dt-body-center',
      },
    ],
    "columns": [
      {
        "data": null,
        render: (data) => {
          return data.status.toUpperCase();
        }
      },
      // {
      //   "data": "outlet"
      // },
      {
        "data": "mobile_number"
      },
      {
        "data": null,
        render: (data) => {
          return data.amount == '0.00' ? "---" : data.amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
      },
      {
        "data": "created_at"
      },
      {
        "data": null,
        render: (data) => {
          return data.reference_code == null ? "---" : data.reference_code
        }
      },
      {
        "data": null,
        render: (data) => {
          return data.note == null ? (data.status == 'completed' ? 'DONE' : '---') : data.note
        }
      },
      {
        "data": null,
        render: (data) => {
          return data.operator.username == null ? "---" : data.operator.username
        }
      },
    ],
    "createdRow": function( row, data, dataIndex){
      if( data.status ==  `failed` ) {
        $(row).find('td').eq(0).attr('style', 'color: red !important');
        $(row).find('td').eq(5).attr('style', 'color: red !important');
      }

      if( data.status ==  `completed` ) {
        $(row).find('td').eq(0).attr('style', 'color: green !important');
      }
    }
  });

  // WITHDRAW TABLE
  var playerWithdrawTable = $('#player-withdraw-table');
  playerWithdrawTable.DataTable({
    "bPaginate": true,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": false,
    "bAutoWidth": false,
    "scrollX": true,
    "order": [[0, 'desc']],
    "ajax": '/player/transaction/withdraw',
    "pageLength": 25,
    "processing": true,
    "serverSide": true,
    "columnDefs": [
      {
        "targets": [2],
        "className": 'dt-body-right',
      },
      {
        "targets": [4, 5],
        "className": 'dt-body-center',
      },
    ],
    "columns": [
      {
        "data": null,
        render: (data) => {
          return data.status.toUpperCase();
        }
      },
      // {
      //   "data": "outlet"
      // },
      {
        "data": "mobile_number"
      },
      {
        "data": null,
        render: (data) => {
          return data.amount == '0.00' ? "---" : data.amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
      },
      {
        "data": "created_at"
      },
      {
        "data": null,
        render: (data) => {
          return data.reference_code == null ? "---" : data.reference_code
        }
      },
      {
        "data": null,
        render: (data) => {
          return data.note == null ? (data.status == 'completed' ? 'DONE' : '---') : data.note
        }
      },
      {
        "data": null,
        render: (data) => {
          return data.operator?.username == null ? "---" : data.operator.username
        }
      },
    ],
    "createdRow": function( row, data, dataIndex){
      if( data.status ==  `failed` ) {
        $(row).find('td').eq(0).attr('style', 'color: red !important');
        $(row).find('td').eq(5).attr('style', 'color: red !important');
      }

      if( data.status ==  `completed` ) {
        $(row).find('td').eq(0).attr('style', 'color: green !important');
      }


    }
  });

  playerWithdrawTable.on('click', 'tbody td .fuego', async function() {
    let id = $(this).data('id');
    let action = $(this).data('action');
    if(action == 'cancel') {
      response = await axios.post('/withdraw/cancel', { id: id });
      Swal.fire({
        icon: 'success',
        confirmButtonColor: 'red',
        title: 'Cancelled successfully',
      }).then(() =>  {
        playerWithdrawTable.DataTable().ajax.reload();
        let playerPts = response.data.points.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        $('#operator-pts').text(playerPts);
      });
    }
    else {
      window.location.href = '/withdraw';
    }
  });

  playerWithdrawTable.on('click', 'tbody td .remove', async function() {
    let id = $(this).data('id');
    let action = $(this).data('action');
    alert('Can\'t remove!');
  });

});



$('[data-bs-toggle="tab"]').on('shown.bs.tab', function(e){
  $($.fn.dataTable.tables(true)).DataTable()
     .columns.adjust();
});
