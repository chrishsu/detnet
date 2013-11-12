<h3>Members</h3>
<div class="notify">
</div>
<div class="admin-box">
<form method="post">
    <label for="member-name-search">Name</label>
    <div class="inline search-box" id="member-search-box">
        <input type="text" class="title" id="member-name-search" name="name" autocomplete="off"/>
        <ul id="member-search-results" class="search-results">
        </ul>
    </div>
    <input type="hidden" id="member-id-search"/>
    <!--<button type="submit" class="hidden" id="member-del">Delete</button>-->
    <button type="submit" class="hidden" id="member-edit">Edit</button>
    
</form>
</div>
<div class="admin-box">
    
    <!--<button type="button" id="member-add">Add Member</button>-->
<form method="post" id="edit-member" class="hidden">
    <h4></h4>
    <div class="row">
        <label for="member-name-input">Name</label>
        <input type="text" class="title" id="member-name-input" name="name" disabled/>
    </div>
    <div class="row">
        <label for="member-assignment-input">Assignment</label>
        <div class="inline search-box">
            <input type="text" class="title" id="member-assignment-input" autocomplete="off"/>
            <input type="hidden" id="member-assignment-id-input" name="assignment-id" value=""/>
            <input type="hidden" id="member-assignment-hash-input" name="assignment-hash" value=""/>
            <ul id="assignment-search-results" class="search-results">
            </ul>
        </div>
    </div>
    <div class="row">
        <label for="member-default-input">Default Group</label>
        <div class="inline search-box">
            <input type="text" class="title" id="member-default-input" autocomplete="off"/>
            <input type="hidden" id="member-default-id-input" name="default-id" value=""/> 
            <ul id="default-search-results" class="search-results">
            </ul>
        </div>
    </div>
    <input type="hidden" id="member-id-input" name="id"/>
    <div class="buttons">
        <button type="submit" class="" id="member-save">Save</button>
        <button type="button" class="" id="member-cancel">Cancel</button>
    </div>
</form>
</div>
<script type="text/javascript" src="js/autocomplete.js"></script>
<script type="text/javascript" src="js/members.admin.js"></script>