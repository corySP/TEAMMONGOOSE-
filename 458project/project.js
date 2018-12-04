
$(document).ready( function() {
    var $projLink = $("#projLink");
    var $maindiv = $("#contents");

    $projLink.click( function() {
        $maindiv.empty();
        getProj();
    });
});

function getProj() {
    $.get("./getProjectList.php", {
        text: "hello"
    }, function(data) {
        var $maindiv = $("#contents");
        var jsonData = JSON.parse(data);
        var html = "<h1>PROJECTS</h1>";

        html += '<div id="selectDiv"><select id="projList">';
        html += '<option hidden disabled selected value> select a project </option>';
        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            html += '<option value="' + result.project_id + '">' 
                    + result.project_name + '</option>';
        }
        html += '<option value="no_val"> ------------------ </option>';
        html += '<option value="add_project"> + ADD PROJECT + </option>';
        html += '</select>';
        //html += '<button id="addProjBtn">Add Project</button>';
        html += '</div><hr /><div id="projectDiv"></div><h3>PROJECT TASKS:</h3><hr /><div id="taskDiv"></div>';
        $maindiv.append(html);

        $("#projList").change( function() {
            if ( $("#projList").val() == "add_project" )
            {
                $("#contents").empty();
                addProjForm();
            }
            else if ( $("#projList").val() == "no_val" )
            {

            }
            else
            {
                var pid = $("#projList").val();
                $("#projectDiv").empty();
                $("#taskDiv").empty();
                getProjInfo(pid);
                getProjTasks(pid);
            }
        });

        /*
        $("#addProjBtn").click( function() {
            $("#contents").empty();
            addProjForm();
        });
        */
    });
}

function getProjInfo(proj_id) {
    var $projectDiv = $("#projectDiv");
    
    $.get("./getProjectInfo.php", {
        project_id: proj_id
    }, function(data) {
        var jsonData = JSON.parse(data);
        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            $projectDiv.append("<h2>" + result.project_name + "</h2>");
            $projectDiv.append("<p>" + result.project_description + "</p>");
        }
    });
    
}

function getProjTasks(proj_id) 
{
    var $taskDiv = $("#taskDiv");

    $.get("./getProjTasks.php", {
        project_id: proj_id
    }, function(data) {
        var jsonData = JSON.parse(data);
        var html = "";

        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            html += '<h2>' + result.task_id + ' : ' + result.task_name + '</h2>';
            html += '<p><b>' + result.task_description + '</b></p>';
            html += '<p> assigned user: ' + result.user_name + '</p>';
            html += '<p> task date: ' + result.task_date + '</p>';
            html += '<p> current status: ' + result.current_status + '</p>';
            html += '<textarea rows="5" cols="80"> ' + result.user_comment + '</textarea>';
            html += '<hr />';
        }

        $taskDiv.append(html);
        $taskDiv.append( '<button id="addTaskBtn"> Add Task </button>');

        $("#addTaskBtn").click( function() {
            $("#contents").empty();
            addTaskForm(proj_id);
        });
    });
}

function addTaskForm(pid)
{
    var $maindiv = $("#contents");
    var html = "";

    html += '<form id="taskForm" onsubmit="event.preventDefault();">';

    html += '<select id="user_list"><option hidden disabled selected value> assign a user </option>';

    $.ajax({
        url: "./getUserList.php", 
        type: 'get',
        async: false,
        success: function(data) {
            var jsonData = JSON.parse(data);
            for (var i=0; i< jsonData.results.length; i++)
            {
                var result = jsonData.results[i];
                html += '<option value="' + result.user_id + '">' 
                        + result.user_name + '</option>';
            }
        }
    });

    html += '</select>';
    html += '<br />';
    html += '<input type="text" id="taskNameFld" placeholder="TASK NAME" required="required" name="task_name"></input>';
    html += '<br />';
    html += '<textarea id="taskDescFld" rows="10" cols="80" placeholder="TASK DESCRIPTION" form="taskForm" required="required"></textarea>';
    html += '<br />';
    html += '<input type="date" id="taskDateFld"></input>';
    html += '<br />';
    html += '<button id="newTaskBtn"> Create Task </button></form>';

    $maindiv.append(html);

    $("#taskForm").submit( function() {
        insertTask(pid);
        $maindiv.empty();
        getProj();
    });
}

function insertTask(pid) {
    var tname = $("#taskNameFld").val();
    var tdesc = $("#taskDescFld").val();
    var uid = $("#user_list").val();
    var tdate = $("#taskDateFld").val();

    $.get("./insertTask.php", {
        project_id: pid,
        task_name: tname,
        task_description: tdesc,
        user_id: uid,
        task_date: tdate
    });
}

function addProjForm()
{
    var $maindiv = $("#contents");
    var html = "";
    html += '<form id="projForm" onsubmit="event.preventDefault();">';
    html += '<input type="text" id="projNameFld" placeholder="PROJECT NAME" required="required" name="project_name"></input>';
    html += '<br />';
    html += '<textarea id="projDescFld" rows="10" cols="80" placeholder="PROJECT DESCRIPTION" form="projForm" required="required"></textarea>';
    html += '<br /><button id="newProjBtn">Create</button></form>';

    $maindiv.append(html);

    $("#projForm").submit( function() {
        insertProject();
        $maindiv.empty();
        getProj();
    });
}

function insertProject() {
    var pname = $("#projNameFld").val();
    var pdesc = $("#projDescFld").val();

    $.get("./insertProject.php", {
        project_name: pname,
        project_description: pdesc
    });
}
