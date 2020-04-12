var firstLoad = true;
getData();

function getData(){
$.getJSON("json/murmur-stats.json", function(result){

    totalUsers = result['users_online'];
    allBootedServers = result['booted_servers'];
    allServers = result['all_servers'];

    oldServerCount = $("#total-booted-servers").text();
    if(firstLoad === false){
        if (oldServerCount >= 4 && oldServerCount < allBootedServers){
            toastr["info"]("New server created!")
        }
    }
    firstLoad = false;


    $("#total-users").html(totalUsers);
    $("#total-booted-servers").html(allBootedServers);
    $("#total-servers").html(allServers);

    $("#individual-servers").empty();

    $.each(result, function(i, field){
        $.each(field, function(j, row){

            friendlyName = row['friendlyName'];
            onlineUsers = row['users_online'];
            bootedServers = row['booted_servers'];
            totalServers = row['all_servers'];

            var html = `
            <div class="stats-box">
            <div class="stats-icon purple"><i class="fas fa-cog"></i></div>
            <div class="stats-content">
                <div class="stats-text">` + friendlyName + `</div>
                <div class="stats-list">
                        <li>Online users: ` + onlineUsers +`</li>
                        <li>Booted servers: ` + bootedServers +`</li>
                        <li>Total servers: ` + totalServers +`</li>
                </div>
            </div>
        </div>
            `

            $("#individual-servers").append(html);


        });
    }); 
});
}

window.setInterval(function(){
    getData();
  }, 5000);

  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-center",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }