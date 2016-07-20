<script type="text/javascript" src="../conf/conf.js"></script>
<script type="application/javascript" src="../js/jquery.jeditable.mini.js"></script>
<script>
	$(document).ready(function() {
		fillList();

		setInterval(updateLists, minutesToMilli(1));

	});

	function minutesToMilli(minutes) {
		return minutes * 60 * 1000
	}



	function fillList() {
		$.getJSON('lib/todoist.php?action=all', function() {

		}).done(function(data) {
			var notesHtml = '<div class="row noteProjects">';
			$.each(data.projects, function(i, project) {
				if (isInArray(project.name, todoSettings.projectsToDisplay)) {
					notesHtml = notesHtml + '<div class="col-xs-12 col-md-5 project"><h3 class="row col-md-12">' + project.name + '</h3><div class="notes">';
					$.each(data.items, function(noteIndex, note) {
						if (note.project_id == project.id) {
							if (note.checked) {
								//								notesHtml = notesHtml + '<div class="row note"><div class="col-md-1 vcenter"><img class="checkbox_checked" src="images/checked.png" data-id="' + note.id + '" onmouseover="mouseOver(this)" onmouseout="mouseOut(this)" onclick="check(this)"></div><div class="col-md-11 noteText vcenter">' + note.content + '</div></div>';
							} else {
								notesHtml = notesHtml + '<div class="row note"><div class="col-md-1 vcenter"><img class="checkbox_unchecked" src="images/unchecked.png" data-id="' + note.id + '" onmouseover="mouseOver(this)" onmouseout="mouseOut(this)" onclick="check(this)"></div><div class="editable col-md-11 noteText vcenter" data-id="' + note.id + '">' + note.content + '</div></div>';
							}
						}

					})
					notesHtml = notesHtml + '<div class="row addNote editable-add" data-id="' + project.id + '"><div class="col-md-1 vcenter"><span class="fui-plus"></span></div><div class="col-md-11 vcenter">Add Item</div></div>';
					notesHtml = notesHtml + '</div></div>';
				}
			});
			notesHtml = notesHtml + '</div';
			$('#Notes').html(notesHtml);
			$('.editable').editable(updateTask, {
				type: 'text',
				submit: 'OK',
				indicator: 'Saving...',
				tooltip: 'Click to edit...',
				cssclass: 'editForm'
			});
			$('.editable-add').editable(newItem, {
				loadurl: 'inc/blankText.php',
				type: 'text',
				submit: 'OK',
				indicator: 'Saving...',
				tooltip: 'Click to add...',
				cssclass: 'editForm',
				height: '50px'
			});
		}).fail(function(xhr) {
			console.log(xhr);
		});

	}

	function updateTask(value, settings) {
		$.getJSON('lib/todoist.php?action=updateItemName&item=' + this.getAttribute('data-id') + '&value=' + value, function() {

		}).done(function(data) {
			fillList();
		});
		return (value);
	}

	function newItem(value, settings) {
		$.getJSON('lib/todoist.php?action=newItem&item=' + this.getAttribute('data-id') + '&value=' + value, function() {

		}).done(function(data) {
			fillList();
		});
		return (value);
	}

	function check(img) {
		img.src = "images/checked.png";
		$.getJSON('lib/todoist.php?action=closeItem&item=' + img.getAttribute('data-id'), function() {

		}).done(function(data) {
			fillList();
		});
	}

	function uncheck(img) {
		img.src = "images/unchecked.png";

	}

	function mouseOver(img) {
		if (img.className.includes("checkbox_checked")) {
			img.src = "images/unchecked.png";
		} else {
			img.src = "images/checked.png";

		}

	}

	function mouseOut(img) {
		if (img.className.includes("checkbox_checked")) {
			img.src = "images/checked.png";

		} else {
			img.src = "images/unchecked.png";

		}
	}

	function isInArray(value, array) {
		return array.indexOf(value) > -1;
	}

	function updateLists() {

	}

</script>
<div id="Notes">
</div>
