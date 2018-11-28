<script role="script" id="twine-user-script" type="text/twine-javascript">
// Begin Inventory Macros
// Original macros by F2Andy: http://strugglingwithtwine.blogspot.ca/2014/03/handling-inventory.html
//
// Instructions:
//
// 1. In a passage, check if there's an item in the inventory...
// ...if not, give the user the option to link to a passage that adds it to inventory:
// <<if $inventory.indexOf("An Unsigned Note") == -1>>There is a note here. [[Pick up the note.]]<<endif>>
//
// 2. In a passage, check if there's an item in the inventory..
// ...if so, give the user a choice to progress to a new passage:
// <<if $inventory.indexOf("The Golden Key") != -1>>[[Unlock the door.]]<<endif>>
//
// 3. To add an "Inventory" link in your sidebar menu, create a passage named "StoryMenu".
// In it, create a link to your inventory's passage: [[Inventory]] or [[Backpack]], for example.
// Create a passage named "Inventory", and in it, write something like the following:
// <<if $inventory.length == 0>>You are not carrying anything.<<else>>You are carrying:
// <<invWithLinks>> <<endif>>
// <<back>>

// A helper function for the following macros.
window.getInv = function() {
  return state.active.variables.inventory;
}

// Starts your inventory. You need to call this once at the start of your game in order to make the inventory work.
// Usage: Place <<initInv>> in your StoryInit passage. Don't have a StoryInit passage? Make one.
macros.initInv = {
  handler: function(place, macroName, params, parser) {
    state.active.variables.inventory = [];
  }
};

// Add an item to your inventory:
// Usage: <<addToInv rock>> or <<addToInv "a smooth rock">>
macros.addToInv = {
  handler: function(place, macroName, params, parser) {
    if (params.length == 0) {
      throwError(place, "<<" + macroName + ">>: no parameters given");
      return;
    }
    if (state.active.variables.inventory.indexOf(params[0]) == -1) {
      state.active.variables.inventory.push(params[0]);
    }
  }
};

// Removes an item from your inventory
// Usage: <<removeFromInv rock>> or <<removeFromInv "a smooth rock">>
macros.removeFromInv = {
  handler: function(place, macroName, params, parser) {
    if (params.length == 0) {
      throwError(place, "<<" + macroName + ">>: no parameters given");
      return;
    }
    var index = state.active.variables.inventory.indexOf(params[0]);
    if (index != -1) {
      state.active.variables.inventory.splice(index, 1);
    }
  }
};

// Display the inventory as a list: Rock, Paper, Scissors
// This can go in any passage, but the best spot would be your [[Inventory]] passage.
// Usage: <<inv>>
macros.inv = {
  handler: function(place, macroName, params, parser) {
    if (state.active.variables.inventory.length == 0) {
      new Wikifier(place, 'nothing');
    } else {
      new Wikifier(place, state.active.variables.inventory.join(", the "));
    }
  }
};

// Display the inventory as a series of links to passages with the same names.
// This can go in any passage, but the best spot would be your [[Inventory]] passage.
// Usage: <<invWithLinks>>
// If those passages don't exist, the links will be broken.
// There is a line break after every item in the inventory.
macros.invWithLinks = { 
  handler: function(place, macroName, params, parser) {
    if (state.active.variables.inventory.length == 0) {
      new Wikifier(place, 'nothing');
    } else {
      new Wikifier(place, '[[' + state.active.variables.inventory.join(']]<br>[[') + ']]');
    }
  }
};

// Empty the inventory entirely.
// Note: This is not like "dropping" an object; they are not added to the current room/passage. It just erases them all entirely.
// Usage: <<emptyInv>>
macros.emptyInv = { 
  handler: function(place, macroName, params, parser) {
    state.active.variables.inventory = []
  }
};
// End Inventory Macros
localStorage.clear();
sessionStorage.clear();
</script>