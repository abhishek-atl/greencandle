const $httpGet = (async(url) => {

    const response = await axios.get(url)
    return response.data
})


const $httpDelete = (async(url) => {

    const response = await axios.delete(url)
    return response.data
})

const $httpPost = (async(url, formData) => {

    const response = await axios.post(url, formData)
    return response.data
})