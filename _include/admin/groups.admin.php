<h3>Groups</h3>
<div class="notify">
</div>
<div class="admin-box">
<form method="post">
    <label for="group-name-search">Name</label>
    <div class="inline search-box" id="group-search-box">
        <input type="text" class="title" id="group-name-search" name="name" autocomplete="off"/>
        <ul id="group-search-results" class="search-results">
        </ul>
    </div>
    <input type="hidden" id="group-id-search"/>
    <button type="submit" class="hidden" id="group-del">Delete</button>
    <button type="submit" class="hidden" id="group-edit">Edit</button>
    
</form>
</div>
<div class="admin-box">
    
    <button type="button" id="group-add">Add Group</button>
<form method="post" id="edit-group" class="hidden">
    <h4></h4>
    <label for="group-name-input">Name</label>
    <input type="text" class="title" id="group-name-input" name="name" /><br>
    <label for="group-description-input">Description</label><br>
    <textarea id="group-description-input" class="text" name="description"></textarea><br>
    <input type="hidden" id="group-id-input" name="id"/>
    <div class="buttons">
        <button type="submit" class="" id="group-save">Save</button>
        <button type="button" class="" id="group-cancel">Cancel</button>
    </div>
</form>
</div>
<script type="text/javascript" src="js/groups.admin.js"></script>