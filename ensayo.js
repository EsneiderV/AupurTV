
export default function isValid(letter) {
   

   if(letter.indexOf('(') !== -1 && letter.indexOf('(')  < letter.indexOf(')')){
        if(letter.includes('[') || letter.includes(']') || letter.includes('{') || letter.includes('{')|| letter.includes('()')){
            return false
        }else{

            return true
        }
   }else{
       return false
   }
   
  }


  console.log(isValid("bici coche (balón) bici coche peluche"))

