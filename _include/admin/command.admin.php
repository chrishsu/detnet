<h3>Commands</h3>
<div class="notify">
</div>
<div class="admin-box">
<form method="post">
    <label for="command-name-search">Name</label>
    <div class="inline search-box" id="command-search-box">
        <input type="text" class="title" id="command-name-search" name="name" autocomplete="off"/>
        <ul id="command-search-results" class="search-results">
        </ul>
    </div>
    <input type="hidden" id="command-id-search"/>
    <button type="submit" class="hidden" id="command-del">Delete</button>
    <button type="submit" class="hidden" id="command-edit">Edit</button>
    
</form>
</div>
<div class="admin-box">
    
    <button type="button" id="command-add">Add Command</button>
<form method="post" id="edit-command" class="hidden">
    <h4></h4>
    <div class="row">
        <label for="command-name-input">Name</label>
        <input type="text" class="title" id="command-name-input" name="name" />
    </div>
    <div class="row">
        <label for="command-parent-input">Parent</label>
        <div class="inline search-box">
            <input type="text" class="title" id="command-parent-input" autocomplete="off"/>
            <input type="hidden" id="command-parent-id-input" name="parent-id" value=""/> 
            <ul id="parent-search-results" class="search-results">
            </ul>
        </div>
    </div>
    <label for="command-description-input">Description</label><br>
    <textarea id="command-description-input" class="text" name="description"></textarea><br>
    <input type="hidden" id="command-id-input" name="id"/>
    <div class="buttons">
        <button type="submit" class="" id="command-save">Save</button>
        <button type="button" class="" id="command-cancel">Cancel</button>
    </div>
</form>
</div>
<script type="text/javascript" src="js/commands.admin.js"></script>