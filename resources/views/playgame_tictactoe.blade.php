<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
          <!-- Favicon-->
          <link rel="icon" type="image/x-icon" href="/imgs/profile.svg" sizes="16x16"/>
    <title>Game Tic tac toe</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<style>
    .h-btn{
        height: 4rem !important;
    }
</style>
</head>
<body>

          <!-- As a heading -->
          <nav class="navbar navbar-dark bg-primary">
            <div class="container-fluid">
              <span class="navbar-brand mb-0 h1">Game Tic tac toe</span>
            </div>
         </nav>
<br>

    <div class="container">
        <form action="/" method="get" id="form-request">
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Player1 Name * :</label>
                    <input type="text" class="form-control check-field" placeholder="player01" value="play1" id="play1" name="play1" >
                  </div>
            </div>

            <div class="col-12 col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Player2 Name * :</label>
                    <input type="text" class="form-control check-field" placeholder="player02" value="play2" id="play2" name="play2" >
                  </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 col-sm-2">
                <div class="mb-3">
                    <label class="form-label fw-bold">Board Size * <span id="span-size">(3x3)</span>:</label>
                    <input type="number" id="number-board" name="board_size" class="form-control" value="3" placeholder="3" onchange="SetBoardSize(this)">
                  </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-6">
                <button type="button" class="btn btn-success" onclick="ApiCreate();">Ok</button>&nbsp;&nbsp;
                <button type="button" class="btn btn-secondary" onclick="Reload();">Reset</button>
            </div>
        </div>
    </form>
      <hr>
    <div class="row">
        <div class="offset-8 col-4  col-md-2 offset-md-10">
            <button type="button" class="btn btn-warning w-100" onclick="ReplayGame();">Replay</button>
        </div>
    </div>
    <br>
    <div class="row" id="spin">
        <div class="col-12 ">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
           
        </div>
    </div>
      <div class="row">
          <div class="col-12" id="content-board">
             
          </div>
      </div>

    </div>


    
   <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script><script>
    var arrBorad     = [];
    var gamePlay     = "play1";
    var namePlayer1  = "";
    var namePlayer2  = "";
    var replayPlayer = [];
    var playId = 0;
    var namePlayWin  = "";

    function ConvertSerialToObj(serialData){
       
        let obj = {};
        serialData.forEach(item=>{
            obj[item.name] = item.value;
        });
        return obj;
    }

    function ApiCreate(){
        namePlayer1 = $("#play1").val();
        namePlayer2 = $("#play2").val();
        let formData = ConvertSerialToObj($('#form-request').serializeArray());
        $.ajax({
        url: "/api_create",
        type:"POST",
        dataType:"json",
        data:{
            formData,
            arrBorad,
            replayPlayer,
            namePlayWin,
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend:function(){
            $("#spin").css("display","block");
        },
        success:function(response){
          GenTableBoard();
          if(response.id != " "){
            playId = response.id;
          }
        },
        complete:function(){
            $("#spin").css("display","none");
        }
       });
    }

    function Reload(){
        location.reload();
    }

    function ReplayGame(){
        let n = replayPlayer.length-2;
        if(n>=0){
            
            GenTableBoard(replayPlayer[n]);
            arrBorad = JSON.parse(JSON.stringify(replayPlayer[n]));
            replayPlayer.pop();
            
        }else{
            arrBorad = [];
            GenTableBoard();
            replayPlayer.pop();
        }
    }

    function CheckPlayGame(){
        let arrBoradlength = arrBorad.length;

        //check row
        arrBorad.forEach((item,key)=>{
            let rowTemp = [];
            
            item.forEach((item2,key2)=>{
                rowTemp.push(item2.value);
            });
            let rowTempSet = new Set(rowTemp);
           
            //row win
            if(!rowTempSet.has("&nbsp;") && rowTempSet.size == 1){
               
                return AlertPlayWin(rowTempSet.values().next().value);
            }
        });

        //check column
        for(let i=0;i<arrBoradlength;i++){
            let colTemp = [];
            for(let j=0;j<arrBoradlength;j++){
                colTemp.push(arrBorad[j][i].value);
            }
            let colTempSet = new Set(colTemp);
            //col win
            if(!colTempSet.has("&nbsp;") && colTempSet.size == 1){
                return AlertPlayWin(colTempSet.values().next().value);
            }
        }

        //check diagonal
        let diagStartTemp = [];
        for(let j=0;j<arrBoradlength;j++){
            diagStartTemp.push(arrBorad[j][j].value);
        }
        let diagStartTempSet = new Set(diagStartTemp);
        //diagStart win
        if(!diagStartTempSet.has("&nbsp;") && diagStartTempSet.size == 1){
            return AlertPlayWin(diagStartTempSet.values().next().value);
        }

        let diagEndTemp = [];
        let i =0;
        for(let j=arrBoradlength-1;j>=0;j--){
            diagEndTemp.push(arrBorad[j][i].value);
            i++;
        }
        let diagEndTempSet = new Set(diagEndTemp);
        //diagEnd win
        if(!diagEndTempSet.has("&nbsp;") && diagEndTempSet.size == 1){
            return AlertPlayWin(diagEndTempSet.values().next().value);
        }
     
    }

    function AlertPlayWin(playName){
       
        let namePlayer = "";
        if(playName == "X"){
            namePlayer = namePlayer1;
        }else{
            namePlayer = namePlayer2;
        }

        if(confirm(namePlayer+" YouWin. Reset plase ok.")){
            namePlayWin = namePlayer;
            ApiCreate();
            Reload();
        }
     
    }

    function CheckField(){
        let statusCheckField = true;
       $(".check-field").each(function(e){
           if($(this).val() == ""){
               alert("please input name player");
               statusCheckField = false;
           }
       });

       return statusCheckField;
    }

    function SetBoardSize(node){
        let sizeNumber = $(node).val();
        let textSize   = `(${sizeNumber}x${sizeNumber})`;
        $("#span-size").text(textSize);
    }

    function GenDataBoard(){
        let numberBoard = $("#number-board").val();

        if(numberBoard == "" || numberBoard <= 0){
           return alert("please input number");
        }else{
            arrBorad = [];
                for(let i = 0;i <numberBoard;i++){
                    let arrBoradItems = [];
                   
                        for(let j=0;j<numberBoard;j++){
                            arrBoradItems[j] = {"item_id":i+"-"+j,"value":"&nbsp;"};
                        }
                        arrBorad[i] = arrBoradItems;
                }
                 return arrBorad;
       
        }
       
    }

  function SetXO(node){
        let val = $(node).text();
        let dataId =$(node).parent().attr("data-id");
        let arrIndex = dataId.split("-");
        if(!isNaN(val)){
            if(gamePlay == "play1"){
                // $(node).text("X");
                arrBorad[arrIndex[0]][arrIndex[1]].value = "X";
                gamePlay = "play2";
            }else{
                // $(node).text("O");
                arrBorad[arrIndex[0]][arrIndex[1]].value = "O";
                gamePlay = "play1";
            }
          let temData = JSON.parse(JSON.stringify(arrBorad));
          
           //set value replay
           replayPlayer.push(temData);
           GenTableBoard(arrBorad);
       
          
        }
    }

     function GenTableBoard(dataBoard){
        if(dataBoard == undefined){
           dataBoard = GenDataBoard();
        }
      
       let checkField = CheckField();
       if(dataBoard && checkField){
       
        let tableBoard = `<table class="table table-bordered">`;
      
       dataBoard.forEach((item,index)=>{
        tableBoard += `<tr>`;
      
        item.forEach((item2,index2)=>{
            
            tableBoard += `<td data-id="${item2.item_id}"><button type="button" class="btn btn-light w-100 h-btn" onclick="SetXO(this);">${item2.value}</button></td>`;
        });
        tableBoard += `</tr>`;
       });
       
       tableBoard += `</table>`;
              $("#content-board").children().remove();
                $("#content-board").append(tableBoard);
       }

        CheckPlayGame();
    
    }
</script>
</body>
</html>