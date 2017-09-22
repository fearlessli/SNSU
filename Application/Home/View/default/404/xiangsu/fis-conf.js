fis.match('*.{png,js,css,less}', {
  release: '/static/$0'
});

fis.match('*', {
  useHash: false
});

fis.match('::packager', {
  postpackager: fis.plugin('loader', {
    //allInOne: true
  })
});
