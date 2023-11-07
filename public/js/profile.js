/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/profile.js":
/*!*********************************!*\
  !*** ./resources/js/profile.js ***!
  \*********************************/
/***/ (() => {

eval("$(function () {\n  $(\"#image\").on(\"change\", function (e) {\n    displaySelectedImage(e, \"selectedAvatar\");\n  });\n});\nvar displaySelectedImage = function displaySelectedImage(event, elementId) {\n  var selectedImage = document.getElementById(elementId);\n  var fileInput = event.target;\n  if (fileInput.files && fileInput.files[0]) {\n    var reader = new FileReader();\n    reader.onload = function (e) {\n      selectedImage.src = e.target.result;\n    };\n    reader.readAsDataURL(fileInput.files[0]);\n  }\n};//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvcHJvZmlsZS5qcyIsIm5hbWVzIjpbIiQiLCJvbiIsImUiLCJkaXNwbGF5U2VsZWN0ZWRJbWFnZSIsImV2ZW50IiwiZWxlbWVudElkIiwic2VsZWN0ZWRJbWFnZSIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJmaWxlSW5wdXQiLCJ0YXJnZXQiLCJmaWxlcyIsInJlYWRlciIsIkZpbGVSZWFkZXIiLCJvbmxvYWQiLCJzcmMiLCJyZXN1bHQiLCJyZWFkQXNEYXRhVVJMIl0sInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvcHJvZmlsZS5qcz85ZTFhIl0sInNvdXJjZXNDb250ZW50IjpbIiQoZnVuY3Rpb24gKCkge1xyXG4gICAgJChcIiNpbWFnZVwiKS5vbihcImNoYW5nZVwiLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgIGRpc3BsYXlTZWxlY3RlZEltYWdlKGUsIFwic2VsZWN0ZWRBdmF0YXJcIik7XHJcbiAgICB9KTtcclxufSk7XHJcbmNvbnN0IGRpc3BsYXlTZWxlY3RlZEltYWdlID0gKGV2ZW50LCBlbGVtZW50SWQpID0+IHtcclxuICAgIGNvbnN0IHNlbGVjdGVkSW1hZ2UgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChlbGVtZW50SWQpO1xyXG4gICAgY29uc3QgZmlsZUlucHV0ID0gZXZlbnQudGFyZ2V0O1xyXG5cclxuICAgIGlmIChmaWxlSW5wdXQuZmlsZXMgJiYgZmlsZUlucHV0LmZpbGVzWzBdKSB7XHJcbiAgICAgICAgY29uc3QgcmVhZGVyID0gbmV3IEZpbGVSZWFkZXIoKTtcclxuXHJcbiAgICAgICAgcmVhZGVyLm9ubG9hZCA9IGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgICAgIHNlbGVjdGVkSW1hZ2Uuc3JjID0gZS50YXJnZXQucmVzdWx0O1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIHJlYWRlci5yZWFkQXNEYXRhVVJMKGZpbGVJbnB1dC5maWxlc1swXSk7XHJcbiAgICB9XHJcbn07XHJcbiJdLCJtYXBwaW5ncyI6IkFBQUFBLENBQUMsQ0FBQyxZQUFZO0VBQ1ZBLENBQUMsQ0FBQyxRQUFRLENBQUMsQ0FBQ0MsRUFBRSxDQUFDLFFBQVEsRUFBRSxVQUFVQyxDQUFDLEVBQUU7SUFDbENDLG9CQUFvQixDQUFDRCxDQUFDLEVBQUUsZ0JBQWdCLENBQUM7RUFDN0MsQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDO0FBQ0YsSUFBTUMsb0JBQW9CLEdBQUcsU0FBdkJBLG9CQUFvQkEsQ0FBSUMsS0FBSyxFQUFFQyxTQUFTLEVBQUs7RUFDL0MsSUFBTUMsYUFBYSxHQUFHQyxRQUFRLENBQUNDLGNBQWMsQ0FBQ0gsU0FBUyxDQUFDO0VBQ3hELElBQU1JLFNBQVMsR0FBR0wsS0FBSyxDQUFDTSxNQUFNO0VBRTlCLElBQUlELFNBQVMsQ0FBQ0UsS0FBSyxJQUFJRixTQUFTLENBQUNFLEtBQUssQ0FBQyxDQUFDLENBQUMsRUFBRTtJQUN2QyxJQUFNQyxNQUFNLEdBQUcsSUFBSUMsVUFBVSxDQUFDLENBQUM7SUFFL0JELE1BQU0sQ0FBQ0UsTUFBTSxHQUFHLFVBQVVaLENBQUMsRUFBRTtNQUN6QkksYUFBYSxDQUFDUyxHQUFHLEdBQUdiLENBQUMsQ0FBQ1EsTUFBTSxDQUFDTSxNQUFNO0lBQ3ZDLENBQUM7SUFFREosTUFBTSxDQUFDSyxhQUFhLENBQUNSLFNBQVMsQ0FBQ0UsS0FBSyxDQUFDLENBQUMsQ0FBQyxDQUFDO0VBQzVDO0FBQ0osQ0FBQyJ9\n//# sourceURL=webpack-internal:///./resources/js/profile.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/profile.js"]();
/******/ 	
/******/ })()
;