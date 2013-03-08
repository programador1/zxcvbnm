
<form name="formularioPagoPatentes" action="#" method="POST">
    <p>
        <label for="selectbox">Seleccione el numero de gestiones a cancelar</label>
        <select id="nroGestiones" name="nroGestiones">
            <option value="1"> 1 Gestion</option>
            <option value="2"> 2 Gestiones</option>
            <option value="3"> 3 Gestiones</option>
            <option value="4"> 4 Gestiones</option>
            <option value="5"> 5 Gestiones</option>
        </select>
    </P>
    <P>
       <div id="divContenidoAjax"> 
           <table>  
               <tr>
                   <th> GESTION </th>
                   <th> Nro</th>
                   <th> Importe </th>
                   <th> Total </th>
                   <th> Tipo </th>               
               </tr>
               <tr>
                   <td> GESTION </td>
                   <td> Nro</td>
                   <td> Importe </td>
                   <td> Total </td>
                   <td> Tipo </td>               
               </tr>
           </table>
       <div>
    </P>
    <p>
        <input class="button" type="submit" value="Imprimir Boleta" name="submit">
        <input class="button" type="submit" value="Cancelar" name="submit">
    </p>
</form>