<h3>Assignments</h3>
<div class="notify">
</div>
<div class="admin-box">
<form method="post">
    <label for="assignment-name-search">Name</label>
    <div class="inline search-box" id="assignment-search-box">
        <input type="text" class="title" id="assignment-name-search" name="name" autocomplete="off"/>
        <ul id="assignment-search-results" class="search-results">
        </ul>
    </div>
    <input type="hidden" id="assignment-id-search"/>
    <button type="submit" class="hidden" id="assignment-del">Delete</button>
    <button type="submit" class="hidden" id="assignment-edit">Edit</button>
    
</form>
</div>
<div class="admin-box">
    
    <button type="button" id="assignment-add">Add Assignment</button>
<form method="post" id="edit-assignment" class="hidden">
    <h4></h4>
    <div class="row">
        <label for="assignment-name-input">Name</label>
        <input type="text" class="title" id="assignment-name-input" name="name" />
    </div>
    <div class="row">
        <label for="assignment-parent-input">Parent</label>
        <div class="inline search-box">
            <input type="text" class="title" id="assignment-parent-input" autocomplete="off"/>
            <input type="hidden" id="assignment-parent-id-input" name="parent-id" value=""/> 
            <ul id="parent-search-results" class="search-results">
            </ul>
        </div>
    </div>
    <div class="row">
        <label for="assignment-default-input">Default Group</label>
        <div class="inline search-box">
            <input type="text" class="title" id="assignment-default-input" autocomplete="off"/>
            <input type="hidden" id="assignment-default-id-input" name="default-id" value=""/> 
            <ul id="default-search-results" class="search-results">
            </ul>
        </div>
    </div>
    <div class="row">
        <label for="assignment-command-input">Command</label>
        <div class="inline search-box">
            <input type="text" class="title" id="assignment-command-input" autocomplete="off"/>
            <input type="hidden" id="assignment-command-id-input" name="command-id" value=""/> 
            <ul id="command-search-results" class="search-results">
            </ul>
        </div>
    </div>
    <label for="assignment-description-input">Description</label><br>
    <textarea id="assignment-description-input" class="text" name="description"></textarea><br>
    <input type="hidden" id="assignment-id-input" name="id"/>
    <div class="buttons">
        <button type="submit" class="" id="assignment-save">Save</button>
        <button type="button" class="" id="assignment-cancel">Cancel</button>
    </div>
</form>
</div>
<script type="text/javascript" src="js/autocomplete.js"></script>
<script type="text/javascript" src="js/assignments.admin.js"></script>