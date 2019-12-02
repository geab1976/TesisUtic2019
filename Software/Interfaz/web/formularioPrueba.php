<div data-role="content" data-theme="e">
    <div class="ui-field-contain">
        <label for="date-1">Date: data-clear-btn="false"</label>
        <input type="date" data-clear-btn="false" name="date-1" id="date-1" value="">
        <label for="date-2">Date: data-clear-btn="true"</label>
        <input type="date" data-clear-btn="true" name="date-2" id="date-2" value="">
    </div>
    <div class="ui-field-contain">
        <div data-role="rangeslider" data-mini="true">
            <label for="range-8a">Rangeslider:</label>
            <input type="range" name="range-8a" id="range-8a" min="0" max="100" value="0">
            <label for="range-8b">Rangeslider:</label>
            <input type="range" name="range-8b" id="range-8b" min="0" max="100" value="100">
        </div>
    </div>
    <div data-role="fieldcontain">
        <label for="temperatura">Temperatura:</label>
        <input type="range" name="temperatura" id="temperatura" min="0" max="100" value="30" data-highlight="true"> °C
    </div>
    <div data-role="fieldcontain">
        <label for="nombre">Nombre:</label>
        <input type="search" name="nombre" id="nombre" value="" />
    </div>
    <div data-role="fieldcontain">
        <fieldset data-role="controlgroup">
            <legend>Fecha de nacimiento:</legend>
            <label for="dia">Día</label>
            <select name="dia" id="dia">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <label for="mes">Mes</label>
            <select name="mes" id="mes">
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
            </select>
            <label for="ano">Año</label>
            <select name="ano" id="ano">
                <option value="1920">1920</option>
                <option value="1921">1921</option>
                <option value="1922">1922</option>
            </select>
        </fieldset>
    </div>
    <div data-role="fieldcontain">
        <label for="poder">Poder:</label>
        <select name="poder" id="poder" data-role="slider">
            <option value="0">Apagado</option>
            <option value="1">Encendido</option>
        </select>
    </div>
    <div data-role="fieldcontain">
        <fieldset data-role="controlgroup">
            <legend>¿Cuál es el mejor tutorial de La Webera?:</legend>
            <input type="radio" name="mejortutorial" id="tutorial1" value="jQuery">
            <label for="tutorial1">jQuery</label>
            <input type="radio" name="mejortutorial" id="tutorial2" value="jQuery Mobile">
            <label for="tutorial2">jQuery Mobile</label>
            <input type="radio" name="mejortutorial" id="tutorial3" value="MooTools">
            <label for="tutorial3">MooTools</label>
        </fieldset>
    </div>
    <div class="ui-grid-a">
        <div class="ui-block-a">
            <div class="ui-bar ui-bar-a" style="height:60px">Block A</div>
        </div>
        <div class="ui-block-b">
            <div class="ui-bar ui-bar-a" style="height:60px">Block B</div>
        </div>
    </div><!-- /grid-a -->
    <div data-role="fieldcontain">
        <fieldset data-role="controlgroup">
            <legend>¿Cuál es son los mejores tutoriales de La Webera?:</legend>
            <input type="checkbox" name="chkT" id="t1" value="jQuery">
            <label for="t1">jQuery</label>
            <input type="checkbox" name="chkT" id="t2" value="jQuery Mobile">
            <label for="t2">jQuery Mobile</label>
            <input type="checkbox" name="chkT" id="t3" value="MooTools">
            <label for="t3">MooTools</label>
        </fieldset>
    </div>
    <div data-role="fieldcontain">
        <legend for="mejortutorial">¿Cuáles son los mejores tutoriales de La Webera?:</legend>
        <select name="mejortutorial" id="mejortutorial">
            <option value="jQuery">jQuery</option>
            <option value="jQuery Mobile">jQuery Mobile</option>
            <option value="MooTools">MooTools</option>
        </select>
    </div>
</div>