function buscadorPorNombre() {

    let input, filter, tr, td, vistaInscrito;
    input = document.getElementById("inputBuscadorNombre");
    filter = input.value.toUpperCase();
    tr = document.querySelectorAll("#tablaTR");
    inputInscritos = document.getElementById('vistaInscritos'); //variable para saber si se consulta de la vista del listado de inscritos
    vistaInscrito = inputInscritos.value;
    
    for (let i = 0; i < tr.length; i++) {
      td = tr[i];
      
      /**
       * valores de la que tiene cada celda de la tabla
       */
      nombre = td.getElementsByTagName("p")[0];
      folio = td.getElementsByTagName("p")[1];
      escuela   = td.getElementsByTagName("p")[2];
      concursos = td.getElementsByTagName('p')[2];
      estado = td.getElementsByTagName('p')[3];

      if(vistaInscrito == 1){
        valorNombre = nombre.textContent || nombre.innerText;
        valorFolio = folio.textContent || folio.innerText;
        valorConcursos = concursos.textContent || concursos.innerText;
        // valorEstado = estado.textContent || estado.innerText;
        /**
         * condicional para filtrar los valores que se escriban en el input "filter"
         * si se sale del indice regresa -1, por lo tanto si el valor da igual a -1 se esconde el TR de la tabla
         * si es mayor a -1 se sigue mostrando 
         */
        if (valorNombre.toUpperCase().indexOf(filter) > -1 || valorFolio.toUpperCase().indexOf(filter) > -1 
          || valorConcursos.toUpperCase().indexOf(filter) > -1 ) {
          
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }else{

        valorNombre = nombre.textContent || nombre.innerText;
        valorFolio = folio.textContent || folio.innerText;
        valorConcursos = concursos.textContent || concursos.innerText;
        valorEstado = estado.textContent || estado.innerText;

        /**
         * condicional para filtrar los valores que se escriban en el input "filter"
         * si se sale del indice regresa -1, por lo tanto si el valor da igual a -1 se esconde el TR de la tabla
         * si es mayor a -1 se sigue mostrando 
         */
        if (valorNombre.toUpperCase().indexOf(filter) > -1 || 
            valorFolio.toUpperCase().indexOf(filter) > -1 || 
            valorEscuela.toUpperCase().indexOf(filter) > -1 || 
            valorConcursos.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
      }
      
      /**FIN  */

      
      
    }

    
}

//FUNCION PARA EL MODAL DE MAS INFORMACION EN LA LISTA DE ASISTENTES
window.onload = function(){
  let botonesInfo = document.querySelectorAll('.botonInfo');
  let divNombre = document.getElementById('divNombre');
  let divNacimiento = document.getElementById('divNacimiento');
  let divEscuela = document.getElementById('divEscuela');
  let divCorreo = document.getElementById('divCorreo');
  let divTelefono = document.getElementById('divTelefono');
  let divCurp = document.getElementById('divCurp');
  let divFolio = document.getElementById('divFolio');
  // let ulConcursos = document.getElementById('ulConcursos');
  let divConcursos = document.getElementById('divConcursos');
  let divEstatus = document.getElementById('divEstatus');
  let divImgAsistente = document.getElementById('divImgAsistente');
  let divImgAsistenteConstancia = document.getElementById('divImgAsistenteConstancia');
  //let divImgAsistenteActa = document.getElementById('divImgAsistenteActa');
  let labelNacimiento = document.getElementById('fNacimiento');
  let labelTelefono = document.getElementById('telefono');
  let labelCurp = document.getElementById('curp');
  let labelIdentificacion = document.getElementById('identificacion');
  //let labelActa = document.getElementById('acta');
  let labelConstancia = document.getElementById('constancia');
  let labelEscuela = document.getElementById('escuela');
  let labelCorreo = document.getElementById('correo');
  let labelConcursos = document.getElementById('concursos');
  botonesInfo.forEach(botonInfo => {
    botonInfo.addEventListener('click', cargarInfoBoton);
  });
  
  function cargarInfoBoton(){
    let valor = this.value;  
    let containerImgAsistente, /*containerImgAsistenteActa,*/ containerImgAsistenteConstancia, imgContainer;
    fetch(`/wsgetuserdata/${valor}`)
        .then(response => response.json())
        .then(data => {
          // console.log(data[1]);
          let pNombreCompleto = document.createElement('p'), pCurp = document.createElement('p');
          let pNacimiento = document.createElement('p'), pTelefono = document.createElement('p');
          let pFolio = document.createElement('p'), pEstatus = document.createElement('p');
          let pEscuela = document.createElement('p'), pCorreo = document.createElement('p');
          let liConcursos , ulConcursos = document.createElement('ul'), item;
          containerImgAsistente = document.createElement('img');
          //containerImgAsistenteActa = document.createElement('img');
          containerImgAsistenteConstancia = document.createElement('img');
          // console.log(data[2].length);
          pNombreCompleto.innerText = `${data[0].nombre} ${data[0].apellido_paterno} ${data[0].apellido_materno}`;
          pNacimiento.innerText = `${data[0].fecha_nacimiento}`;
          pCorreo.innerText = `${data[0].correo_electronico}`;
          pEscuela.innerText = `${data[0].escuela_origen}`;
          pTelefono.innerText = `${data[0].telefono}`;
          pCurp.innerText = `${data[0].curp}`;
          
          data[0].folio ? pFolio.innerText = `${data[0].folio}` : pFolio.innerText = 'Alumno sin número de folio';
          
          if (data[1]) {
            if (data[1].estado == 'Espera') {
              pEstatus.innerText = `En revisión`;
            }else if(data[1].estado == 'Rechazado'){
              pEstatus.innerText = `Renvío`;
            }else{
              pEstatus.innerText = `Inscrito`;
            } 
            for (let i = 0; i < data[2].length; i++) {
              // console.log(data[2][i].nombre);
              liConcursos = document.createElement('li')
              liConcursos.appendChild(document.createTextNode(data[2][i].nombre));
              ulConcursos.appendChild(liConcursos);
              
            }
            labelNacimiento.style.display = '';
            labelTelefono.style.display = '';
            labelEscuela.style.display = '';
            labelCorreo.style.display = '';
            labelCurp.style.display = '';
            labelConcursos.style = ''
            labelIdentificacion.style.display = '';
            //labelActa.style.display = '';
            labelConstancia.style.display = '';
            containerImgAsistente.src = `/storage/${data[0].documentos.foto_asistente}`;
            //containerImgAsistenteActa.src = `/storage/${data[0].documentos.acta_asistente}` ;
            containerImgAsistenteConstancia.src = `/storage/${data[0].documentos.constancia_escuela}`;
            divNombre.appendChild(pNombreCompleto);
            divNacimiento.appendChild(pNacimiento);
            divEscuela.appendChild(pEscuela);
            divCorreo.appendChild(pCorreo);
            divTelefono.appendChild(pTelefono);
            divCurp.appendChild(pCurp);
            divFolio.appendChild(pFolio);
            divConcursos.appendChild(ulConcursos);
            divEstatus.appendChild(pEstatus);
            divImgAsistente.appendChild(containerImgAsistente);
            //divImgAsistenteActa.appendChild(containerImgAsistenteActa);
            divImgAsistenteConstancia.appendChild(containerImgAsistenteConstancia);
          }else{
            divNombre.appendChild(pNombreCompleto);
            divFolio.appendChild(pFolio);
            pEstatus.innerText = `Enviando`;
            divEstatus.appendChild(pEstatus);
            labelNacimiento.style = 'display:none;'
            labelEscuela.style = 'display:none;'
            labelCorreo.style = 'display:none;'
            labelTelefono.style = 'display:none;'
            labelCurp.style = 'display:none;'
            labelConcursos.style = 'display:none;'
            labelIdentificacion.style = 'display:none;'
            //labelActa.style = 'display:none;'
            labelConstancia.style = 'display:none;'
            
          }
          
          // console.log(data[0].documentos);
          // console.log(data);
        });
        divNombre.innerHTML = '';
        divNacimiento.innerHTML = '';
        divTelefono.innerHTML = '';
        divEscuela.innerHTML = '';
        divCorreo.innerHTML = '';
        divCurp.innerHTML = '';
        divConcursos.innerHTML = '';
        divFolio.innerHTML = '';
        divEstatus.innerHTML = '';
        divImgAsistente.innerHTML = '';
        //divImgAsistenteActa.innerHTML = '';
        divImgAsistenteConstancia.innerHTML = '';
  }

}
//FIN FUNCION MODAL