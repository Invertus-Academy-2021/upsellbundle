var description = $(description).text();
//const myJSON = JSON.stringify(obj);


function downloadOutlineToPDF()
{   
    var element = document.getElementById('main').children[1];
    var opt = {
        margin:       0,
        filename:     productname +'.pdf',
        image:        { type: 'jpeg', quality: 1 },
        html2canvas:  { scale: 1 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
      };
    html2pdf().set(opt).from(element).save();
}

function downloadDetailsToPDF()
{  
     const myJSON = JSON.stringify(currency);
     console.log(myJSON);
    
    var doc = new jsPDF('p', 'in', 'letter'),
    sizes = [12, 16, 20],
    fonts = [['Times', 'Roman'], ['Helvetica', ''], ['Times', 'Italic']],
    font, size, lines,
    margin = 0.5,
    verticalOffset = margin
    font = fonts[0]

    //Product image
    
    if(isImageDisplayed == true){
        doc.addImage(imageBase64, 'JPEG', 4, 3, 4, 4)
    }

    //Product name
    doc.text(0.5, verticalOffset + sizes[2] / 72, productname)
    verticalOffset += (2 + 0.5) * sizes[2] / 72

    //Product description
    lines = doc.setFont(font[0], font[1])
                    .setFontSize(sizes[0])
                    .splitTextToSize(description, 7.5)
    doc.text(0.5, verticalOffset + sizes[0] / 72, lines)
    verticalOffset += (lines.length + 2) * sizes[0] / 72

    //Product price
    if(isPriceDisplayed == true){
        doc.text(0.5, verticalOffset + sizes[1] / 72, 'Price:  '+ currency + price)
        verticalOffset += (2 + 0.5) * sizes[1] / 72
    }

    //Product features, example: Composition - Cotton
    if(isFeaturesDisplayed == true){
        if(!features === undefined || !features.length == 0){
        features.forEach((element) => {
            doc.text(0.5, verticalOffset + sizes[1] / 72, element.name  +': ' + element.value)
            verticalOffset += (2 + 0.5) * sizes[1] / 72
        });}
    }

    //Product attributes, example: Size - S, M, L
    if(isAttributesDisplayed == true){
        if(!attributes === undefined || !attributes.length == 0){
            attributesList = [];
            attributes.forEach((element) => {
                attributesList.push(element.attribute);
            });
            doc.text(0.5, verticalOffset + sizes[1] / 72, 'Available ' +  attributes[0].group.toLowerCase() +': ' + attributesList.join(', '))
            verticalOffset += (2 + 0.5) * sizes[1] / 72
        }
    }   
     doc.save(productname +".pdf");
}

   

