let it = 0;

export default (prefix = 'id') => {
  it++;

  return `${prefix}-${new String(it).padStart(8, "0")}${Math.floor(Math.random() * 1000)}${Math.floor(new Date().getUTCMilliseconds())}`;
}
