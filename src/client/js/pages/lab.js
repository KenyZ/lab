import "../../css/pages/lab.scss"

console.log("Hello world in lab.js")

const listOpener = document.getElementById("listOpener")
const labList = document.getElementById("labList")


listOpener.addEventListener("click", () => {
    labList.classList.toggle("closed")
})