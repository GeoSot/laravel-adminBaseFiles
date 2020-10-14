const getScriptElement = (SRC)=>{
    let script = document.createElement('script');
    script.async = true;
    script.defer = true;
    script.type = 'text/javascript';
    script.src = '//' + SRC;
    script.id = SRC;
    return script;
}

const load = (scriptSrc)=>{
    return new Promise((resolve, reject)=>{
        if (!scriptSrc)
        {
            reject();
        }
        let SRC = scriptSrc.replace(/^https?:\/\//i, '').replace(/^\/\//i, '');

        const existingScript = document.getElementById(SRC);
        if (existingScript)
        {
            resolve(scriptSrc);
        }

        if (!existingScript)
        {
            let script = getScriptElement(SRC);
            document.body.appendChild(script);

            script.onload = ()=>{
                resolve(scriptSrc);
            };
        }


    });


}
module.exports = {load};

