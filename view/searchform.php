<?php
    namespace view;
?>

<div class="col-lg-8 col-md-10 mx-auto">
  <form id="contactForm" name="sentMessage" accept-charset="UTF-8" novalidate>
    <div class="control-group">
      <div class="form-group floating-label-form-group controls">
        <label id="searchLabel" for="keyword">Titolo</label>
        <input id="keyword" type="text" class="form-control" placeholder="Titolo" min="3" required>
        <p class="help-block text-danger"></p>
      </div>
      <input id="searchByTitle" type="radio" name="search" value="title" checked>&nbsp;<label for="searchByTitle">Titolo</label>
      <input id="searchByAuthor" type="radio" name="search" value="author">&nbsp;<label for="searchByAuthor">Autore</label>
      <input id="searchByVoice" type="radio" name="search" value="voice" style="display: none;">&nbsp;<label id="voiceLabel" for="searchByVoice" style="display: none;">Doppiatore</label>
    </div>
    <br />
    <div class="control-group" style="margin-top: 5px;">
      <button id="sendMessageButton" type="submit" class="btn btn-primary">Cerca</button>
      <button id="clear" type="button" class="btn btn-primary" style="display: none;" disabled>Cancella i risultati</button>
      <button id="deepSearch" type="button" class="btn btn-primary" style="display: none;">Approfondisci</button>
    </div>
    <br/>
    <div id="success"></div>
  </form>
</div>
