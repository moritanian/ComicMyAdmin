<script src="<?= $v->app_pos?>/Plugins/Three/three.min.js"></script>
<script src="<?= $v->app_pos?>/Plugins/Three/OrbitControls.js"></script> 



<script>
$(function(){

  var Libre3D = function(books_data, search_call, info_call){
    /*
    階層
    scene
      books
        book
          book_data
            url
            img_url
          book_box
            mesh(box)
            mesh(image)
            
    */
    var isControl = 0;

    var cylinder_img = "../Images/wood2.jpg";
    var no_image = "../Images/no_image.png";
    var mouse = new THREE.Vector2(), INTERSECTED;
    // create scene
    var scene = new THREE.Scene();
    // create camera
    var width  = 800;
    var height = 500;
    var fov    = 60;
    var aspect = width / height;
    var near   = 10;
    var far    = 1000;
    var camera = new THREE.PerspectiveCamera( fov, aspect, near, far );

    var itemCount = 0;
    var itemMax = books_data.length;
    if(isControl){
      var controls = new THREE.OrbitControls(camera);
    }
    // set renderrer
    camera.position.set(0, 10, 90);
    camera.lookAt(new THREE.Vector3(0,10, -50));
    var renderer = new THREE.WebGLRenderer({ antialias: true });
      renderer.setSize( width, height );
      $("body").append( renderer.domElement );
    renderer.shadowMap.enabled = true;//影をつける(1)
      
    // add light
    var directionalLight = new THREE.DirectionalLight( 0x7777777 );
    directionalLight.position.set( 0, 100, 0 );
    directionalLight.castShadow = true;//影をつける（2）
    scene.add( directionalLight );
    light2 = new THREE.AmbientLight(0xaaaaaa);
    scene.add(light2);  


    var size = { 'x':11, 'y':16.0, 'z':0.8 };
    // create box 
    function createBox(pos, rot, size, pos_id = 0, book_data = {}){
      
      var book = new THREE.Group();

      var geometry = new THREE.BoxBufferGeometry( size.x, size.y, size.z);

      var random = Math.random() * 0xffffff;
      var gray = 0.01;
      var color = Math.ceil((random & 0xff0000) * gray) | Math.ceil((random & 0x00ff00) * gray) | Math.ceil((random & 0xff) * gray);
      var material = new THREE.MeshPhongMaterial( { color : color } );
    
      //var material = new THREE.MeshNormalMaterial();
      var mesh = new THREE.Mesh( geometry, material );
    
      var book_box = new THREE.Group();

      book_box.add(mesh);

      var mesh = getImageMesh(book_data.img_url, size);
     

      book_box.add(mesh);
      book.add(book_box);

      applyPos(book, pos, rot);

      book.book_data = book_data;
      book.pos_id = pos_id;

      return book;
    }

    function isInvalidImgURL(img_url){
      var img = new Image();
      img.src = img_url;
      var width  = img.width;  // 幅
      var height = img.height; // 高さ
      console.log(img_url + width + "  " +  height);
      return width == 0 || height == 0;
    }

    function getImageMesh(img_url, size){ 
      var geometry = new THREE.PlaneGeometry ( size.x, size.y , 1, 1 );
      var textureLoader = new THREE.TextureLoader(); 
      
      if(!img_url || img_url == "" || isInvalidImgURL(img_url)){
        //img_url = no_image;
      }

      var texture = textureLoader.load(img_url);



      var material = new THREE.MeshBasicMaterial( {
         map : texture
      } );
      
      material.minFilter = THREE.LinearFilter;
      var mesh = new THREE.Mesh( geometry, material );
      mesh.minFilter = THREE.LinearFilter;
      mesh.position.set(0,0,size.z * 0.6);

      console.log(texture);
      return mesh;
    }

    function applyBookData(pos_id, book_data){
      var book = books.children[pos_id];

      var book_box = book.children[0];
      var img_mesh = book_box.children[1];
      book_box.remove(img_mesh);
      book_box.add(getImageMesh(book_data.img_url, size));
      book.book_data = book_data;      
    }

    function addBook(id, book_data){
      var pos ={};
      var rot = {};
      getBookPos(pos, rot, id);
      var book = createBox(pos, rot, size, id, book_data);
      books.add(book);
      
    }

    var bookPerRot = 32;
    function getBookPos(pos, rot, id){
      var sita = Math.PI*(id/bookPerRot*2);
      var rad = 70;
      rot.x = 0;
      rot.y = -(sita + Math.PI*1.5);
      rot.z = 0;
      pos.x = rad*Math.cos(sita);
      pos.y = (0.7)*size.y;
      pos.z = rad*Math.sin(sita) ;
    }

    function applyPos(obj, pos, rot){
      obj.position.set(pos.x, pos.y, pos.z);
      obj.rotation.set(rot.x, rot.y, rot.z);
    } 
  
    var meshes = [];
  
   
    var books = new THREE.Group();
    for(var i=0; i<bookPerRot; i++){
      addBook(i, {title: books_data[itemCount].title, recordLabel: books_data[itemCount].recordLabel, author: books_data[itemCount].author, url: books_data[itemCount].amazonItemURL, id:1, img_url: books_data[itemCount].imgURL});
      itemCount ++;
      if(itemCount == itemMax)itemCount=0;
    }
    scene.add(books);
    var point_book;

    //床の描画
    var n_yuka = 10, yuka_w = 5; 
    var yuka_size = 1;
    for(var i=-n_yuka; i<=n_yuka ; i++){
      for(var j=-n_yuka; j<=n_yuka ; j++){
        if((i+j)%2==0) var plane = new THREE.Mesh(
              new THREE.PlaneGeometry(yuka_w, yuka_w, 1, 1), 
              new THREE.MeshLambertMaterial({color: 0x999999}));
          else var plane = new THREE.Mesh(
              new THREE.PlaneGeometry(yuka_w, yuka_w, 1,1), 
              new THREE.MeshLambertMaterial({color: 0x101010}));
        plane.position.x = j*yuka_w;
        plane.position.z = i*yuka_w + 30;
        plane.position.y = 0;
        plane.rotation.x = -Math.PI/2.0;
        plane.receiveShadow = true;
        scene.add(plane);
      }
    }
    
    // 円柱
    var createCylinder = function(){
      var textureLoader = new THREE.TextureLoader(); 
      var material = new THREE.MeshBasicMaterial( {
         map : textureLoader.load(cylinder_img)
      } );
      var Cylinder = new THREE.Mesh(                                     
      new THREE.CylinderGeometry(50,50,70,50),                         
      material);
      Cylinder.position.y += 20;
      Cylinder.rotation.y = Math.PI;
      scene.add(Cylinder);

    }
    createCylinder();

    raycaster = new THREE.Raycaster();
    document.addEventListener( 'mousemove', onDocumentMouseMove, false );
    document.addEventListener('mousedown', onMouseDown, false);

    // rendering
    if(isControl)controls.update();　
    renderer.render( scene, camera );
    
    var bookPerSec = 0.5;
    var count = 0;

    var setRotateSpeed = function(speed){
      bookPerSec = speed;
      console.log("speed = " + speed);
    }
    setInterval(function(){
      books.rotation.y += Math.PI/(bookPerRot/bookPerSec)/10;
      if(books.rotation.y > 2*Math.PI/bookPerRot*(count+1) ){
        var pos_id = count % bookPerRot;
        applyBookData(pos_id, {title: books_data[itemCount].title, author: books_data[itemCount].author, recordLabel: books_data[itemCount].recordLabel, url: books_data[itemCount].amazonItemURL, id:1, img_url: books_data[itemCount].imgURL});
       // addBook( Math.PI*(0.5 + 0.25 + count * 2/bookPerRot));
        count++;
        itemCount++;
        if(itemCount == itemMax)itemCount=0;
      }
    }, 50);

    ( function renderLoop () {
    requestAnimationFrame( renderLoop );
    renderer.render( scene, camera );

    } )();

    function onDocumentMouseMove( event ) {
      event.preventDefault();
      mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
      mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;
      var rect = event.target.getBoundingClientRect();

      // スクリーン上のマウス位置を取得する
      var mouseX = event.clientX - rect.left;
      var mouseY = event.clientY - rect.top;
      mouseX = (mouseX/width)  * 2 - 1;
      mouseY = -(mouseY/height) * 2 + 1;
      // マウスの位置ベクトル
      var pos = new THREE.Vector3(mouseX, mouseY, 1);

      // pos はスクリーン座標系なので、オブジェクトの座標系に変換
      // オブジェクト座標系は今表示しているカメラからの視点なので、第二引数にカメラオブジェクトを渡す
      // new THREE.Projector.unprojectVector(pos, camera); ↓最新版では以下の方法で得る
      pos.unproject(camera);

      // 始点、向きベクトルを渡してレイを作成
      var ray = new THREE.Raycaster(camera.position, pos.sub(camera.position).normalize());
      //var ray = new THREE.Raycaster(camera.position, new THREE.Vector3(0,0,-1));
      // 交差判定
      // 引数は取得対象となるMeshの配列を渡す。以下はシーン内のすべてのオブジェクトを対象に。
      var objs = ray.intersectObjects(books.children, true);

      //ヒエラルキーを持った子要素も対象とする場合は第二引数にtrueを指定する
      //var objs = ray.intersectObjects(scene.children, true);
      var jump_z = 3;
      if (objs.length > 0) {
        
            
        if(point_book && point_book != objs[0].object.parent){
         point_book.position.z -= jump_z;
        }
        if(point_book != objs[0].object.parent){
          point_book = objs[0].object.parent;
          point_book.position.z += jump_z;
          var p_book_data = point_book.parent.book_data;
          var title = p_book_data.title;
          var recordLabel = p_book_data.recordLabel;
          var author = p_book_data.author;
          var text = "title : " + title + "\n <br> author : " + author + "\n <br>recordLabel : " + recordLabel;
           info_call(text);
        }
      }else{
        if(point_book){
          point_book.position.z -=jump_z;
          point_book = null;
        }
      }
    }

    function onMouseDown(){
      if(point_book){
        window.open(point_book.parent.book_data.url);
      }
    }

    $("#search-button").click(function(){
      search();
    });
    var search = function(){
      var search_text  = $("#search-text").val();
      console.log(search_text);
      // サーチワードで検索時のコールバック(引数が検索結果)
      var callBack = function(data){
        info_call( "hit (" + data.length  + ")  <br>    word:   " + search_text);
        if(data.length == 0)return;
        itemCount = 0;
        itemMax = data.length;
        books_data = data;
        console.log(JSON.stringify(books_data));
        var speed = 15;
        setRotateSpeed(speed);
        setTimeout(function(){setRotateSpeed(0.5)}, bookPerRot*3/4/speed * 1000);
      }
      search_call(search_text, callBack);
     
    }

  };

  var makeTextSprite = function(message, parameters) {
      if (parameters === undefined)
        parameters = {};
      var fontface = parameters.hasOwnProperty("fontface") ? parameters["fontface"] : "Arial";
      var fontsize = parameters.hasOwnProperty("fontsize") ? parameters["fontsize"] : 18;
      var borderThickness = parameters.hasOwnProperty("borderThickness") ? parameters["borderThickness"] : 4;
      var borderColor = parameters.hasOwnProperty("borderColor") ? parameters["borderColor"] : {
        r: 0,
        g: 0,
        b: 0,
        a: 1.0
      };
      var backgroundColor = parameters.hasOwnProperty("backgroundColor") ? parameters["backgroundColor"] : {
        r: 255,
        g: 255,
        b: 255,
        a: 1.0
      };
      var canvas = document.createElement('canvas');
      this.context = canvas.getContext('2d');
      this.context.font = "Bold " + fontsize + "px " + fontface;
      var metrics = this.context.measureText(message);
      var textWidth = metrics.width;
      this.context.fillStyle = "rgba(" + backgroundColor.r + "," + backgroundColor.g + "," + backgroundColor.b + "," + backgroundColor.a + ")";
      this.context.strokeStyle = "rgba(" + borderColor.r + "," + borderColor.g + "," + borderColor.b + "," + borderColor.a + ")";
      this.context.lineWidth = borderThickness;
      this.roundRect(this.context, borderThickness / 2, borderThickness / 2, textWidth + borderThickness, fontsize * 1.4 + borderThickness, 6);
      this.context.fillStyle = "rgba(0, 0, 0, 1.0)";
      this.context.fillText(message, borderThickness, fontsize + borderThickness);
      this.texture = new THREE.Texture(canvas);
      this.texture.needsUpdate = true;
      var spriteMaterial = new THREE.SpriteMaterial({
        map: this.texture,
        useScreenCoordinates: false
      });
      var sprite = new THREE.Sprite(spriteMaterial);
      sprite.scale.set(100, 50, 1.0);
      return sprite;
  }

  var getBookData = function(send_data, callBack){ 
    console.log("getBookData");
    console.log( JSON.stringify(send_data));
    var libre3D;
    $.ajax({
      url: '../API/LibreData.php',
      type: 'POST',
      contentType : "application/json",
      cache: 'false',
      dataType: 'json',
      data: JSON.stringify(send_data),
    })
    .done(function (data, textStatus, jqXHR) {
      callBack(data);
    })
    .fail(function(jqXHR, testStatus, errorThrown){
      alert("failed \n" + testStatus + "\n" + errorThrown);
    });
  }

  // サーチワード検索時のコールバック(サーチワード => 書籍データへの変換処理) callback は引数が書籍データ
  var search_call = function(search_text, callBack){
    if(search_text == "")return;
    getBookData({is_monthly_new: false, search_text: search_text}, callBack);
  }

  // 書籍選択時などのコールバック処理
  var info_call = function(info_data){
    $("#info-text").attr("class", "rotate-ini rotate-z");
    setTimeout(function(){
        $("#info-text").attr("class", "rotate-ini");
        $("#info-text").html("<div id='#info-text'>" + info_data + "</div>");
      }, 200);
    
  }
  var callBack = function(data){
    libre3D = new Libre3D(data, search_call, info_call);
    $(".Libre3D").append($("canvas"));
  }

  getBookData({is_monthly_new: true, search_text: ""}, callBack);

  jQuery.fn.exists = function(){
    return Boolean($("body:has(" + this.selector + ")").length > 0);
  }   
  
 
  $("body")
        .css("background-size", "600px, 300px");

});


</script>
<div class="d3-content">
  <div class="contents-box">
    <h3>LIBRE3D</h3>  
    <div class="search">
      <input type="text"　value="" id="search-text"></input>
      <input type="button" value="検索" id="search-button" ></input>
    </div>
  </div>
  <div class="Libre3D">
  </div>
  <div id="info-text" class="rotate-ini">

  </div>
</div>
